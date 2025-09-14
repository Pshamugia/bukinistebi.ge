<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\OrderPurchased;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderInvoice;             // <—
use Illuminate\Support\Facades\Cache;  // <— for idempotency


class PaymentCallbackController extends Controller
{
    protected $success_status = "Succeeded";

    // callback for auction
    public function handle(Request $request)
    {
        $paymentId = $request->input('PaymentId');


        Log::info("Got paymentID from cb:", [
            "paymentID" => $paymentId,
        ]);

        if (!$paymentId) {
            Log::warning('Callback received without PaymentId');
            return response()->json(['error' => 'Missing PaymentId'], 400);
        }

        $token = $this->getAccessToken();
        if (!$token) {
            Log::error('Failed to get access token in callback');
            return response()->json(['error' => 'Auth error'], 500);
        }

        //$response = Http::withToken($token)->get(env('TBC_BASE_URL') . "/v1/tpay/payments/$paymentId");
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'apikey' => env('TBC_API_KEY'),
            'Content-Type' => 'application/json',
        ])->get(env('TBC_BASE_URL') . "/v1/tpay/payments/$paymentId");


        if (!$response->ok()) {
            Log::error('Failed to fetch payment info from TBC', [
                'paymentId' => $paymentId,
                'response' => $response->body()
            ]);
            return response()->json(['error' => 'Payment fetch failed'], 500);
        }

        $data = $response->json();

        if (($data['status'] ?? '') !== $this->success_status) {
            Log::info('Payment not finalized yet or not successful', ['paymentId' => $paymentId, 'status' => $data['status'] ?? null]);
            return response()->json(['status' => 'not finalized'], 200);
        }


        // extracting from generated id
        $parts = explode('-', $data['merchantPaymentId'] ?? '');
        if (count($parts) < 5 || $parts[0] !== 'AUC' || $parts[1] !== 'FEE') {
            Log::warning('Invalid merchantPaymentId format', [
                'merchantPaymentId' => $data['merchantPaymentId'] ?? 'null'
            ]);
            return response()->json(['error' => 'Invalid format'], 400);
        }

        $userId = (int) $parts[2];
        $auctionId = (int) $parts[3];

        DB::beginTransaction();
        try {
            $updated = DB::table('auction_users')
                ->where('user_id', $userId)
                ->where('auction_id', $auctionId)
                ->whereNull('paid_at')
                ->update([
                    'paid_at' => now(),
                    'updated_at' => now(),
                ]);

            if ($updated) {
                DB::commit();
                Log::info('Auction fee marked as paid', compact('userId', 'auctionId'));
            } else {
                DB::rollback();
                Log::warning('No unpaid auction_users row found to update', compact('userId', 'auctionId'));
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Database error in payment callback', [
                'error' => $e->getMessage(),
                'userId' => $userId,
                'auctionId' => $auctionId
            ]);
        }

        return response()->json(['status' => 'ok'], 200);
    }

    // callback for book bought
    public function handleBookBought(Request $request)
    {
        $paymentId = $request->input('PaymentId');

        Log::info("Got paymentID from cb book payment:", [
            "paymentID" => $paymentId,
        ]);

        if (!$paymentId) {
            Log::warning('Callback received without PaymentId');
            return response()->json(['error' => 'Missing PaymentId'], 400);
        }

        $token = $this->getAccessToken();
        if (!$token) {
            Log::error('Failed to get access token in callback');
            return response()->json(['error' => 'Auth error'], 500);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'apikey' => env('TBC_API_KEY'),
            'Content-Type' => 'application/json',
        ])->get(env('TBC_BASE_URL') . "/v1/tpay/payments/$paymentId");


        if (!$response->ok()) {
            Log::error('Failed to fetch payment info from TBC', [
                'paymentId' => $paymentId,
                'response' => $response->body()
            ]);
            return response()->json(['error' => 'Payment fetch failed'], 500);
        }

        $data = $response->json();
        $paymentStatus = $data['status'] ?? '';
        
        /** Load order + relations for stock math and email */
        $order = Order::with(['orderItems.book', 'orderItems.bundle.books', 'user'])
            ->where('gate_id', $paymentId)
            ->first();
        
        if (!$order) {
            Log::warning("Can't find order for paymentId {$paymentId}");
            return response()->json(['error' => 'Order not found'], 404);
        }
        
        /** Guard: callbacks can fire multiple times — lock per order to avoid double side-effects */
        $lock = Cache::lock('payment_cb_'.$order->id, 15);
        if (!$lock->get()) {
            Log::info('Duplicate callback suppressed', ['order_id' => $order->id]);
            return response()->json(['status' => 'duplicate'], 200);
        }
        
        try {
            // Update status early so you can inspect from admin if something fails later
            $order->status = $paymentStatus ?: 'Pending';
            $order->save();
        
            if ($paymentStatus === "Succeeded") {
        
                /** DIRECT-PAY path — you already handle a subset; fix bundle math (qty * pivot->qty) */
                if (str_starts_with($order->order_id, 'ORD-DIRECT-')) {
        
                    foreach ($order->orderItems as $item) {
                        if ($item->book) {
                            // Single book
                            if ($item->book->quantity >= $item->quantity) {
                                $item->book->decrement('quantity', $item->quantity);
                            }
                        } elseif ($item->bundle) {
                            // Bundle: decrement each member by item qty × bundle pivot qty
                            foreach ($item->bundle->books as $b) {
                                $need = (int)$item->quantity * (int)max(1, (int)$b->pivot->qty);
                                if ($need > 0 && $b->quantity >= $need) {
                                    $b->decrement('quantity', $need);
                                } else {
                                    Log::info('Bundle member short on stock', [
                                        'book_id' => $b->id,
                                        'need'    => $need,
                                        'have'    => $b->quantity,
                                    ]);
                                }
                            }
                        }
                    }
        
                } else {
                    /** CART checkout path: reduce using cart contents, then clear cart */
                    $cart = optional($order->user)->cart;
        
                    if ($cart) {
                        $quantityUpdateErrs = [];
        
                        foreach ($cart->cartItems as $cartItem) {
                            if ($cartItem->book) {
                                if ($cartItem->book->quantity >= $cartItem->quantity) {
                                    $cartItem->book->decrement('quantity', $cartItem->quantity);
                                } else {
                                    $quantityUpdateErrs[] = $cartItem->book->id;
                                }
                            }
        
                            if ($cartItem->bundle) {
                                foreach ($cartItem->bundle->books as $b) {
                                    $need = (int)$cartItem->quantity * (int)max(1, (int)$b->pivot->qty);
                                    if ($b->quantity >= $need) {
                                        $b->decrement('quantity', $need);
                                    } else {
                                        $quantityUpdateErrs[] = $b->id;
                                    }
                                }
                            }
                        }
        
                        // Clear cart after successful stock ops
                        $cart->cartItems()->delete();
                        $cart->delete();
        
                        if (!empty($quantityUpdateErrs)) {
                            Log::info("Failed to update some book quantities", [
                                'failed_books' => $quantityUpdateErrs,
                                'paymentId'    => $paymentId,
                            ]);
                        }
                    }
                }
        
                /** Send emails
                 *  - Admin (you already do)
                 *  - Customer invoice (new): authenticated buyer only, or fallback if you store email on order/session
                 */
                $order->refresh()->loadMissing(['orderItems.book', 'orderItems.bundle.books', 'user']);
        
                // Admin notification (kept)
                Mail::to('pshamugia@gmail.com')->send(new OrderPurchased($order, 'bank_transfer'));
        
                // Customer invoice (authenticated users)
                $customerEmail = optional($order->user)->email
                    ?? ($order->email ?? (session('checkout_email') ?: null)); // optional fallback if you later add order email
        
                if ($customerEmail) {
                    Mail::to($customerEmail)->send(new OrderInvoice($order));
                }
            } else {
                // Not succeeded — no stock change, no invoice
                Log::info('Payment not finalized or failed', [
                    'paymentId' => $paymentId, 'status' => $paymentStatus
                ]);
            }
        
            Log::info('Payment callback handled', [
                'id' => $paymentId, 'status' => $paymentStatus, 'order_id' => $order->id,
            ]);
        
            return response()->json(['status' => 'ok'], 200);
        
        } finally {
            optional($lock)->release();
        }
        
    }

    private function getAccessToken()
    {
        $response = Http::asForm()->withHeaders([
            'apikey' => env('TBC_API_KEY'),
        ])->post('https://api.tbcbank.ge/v1/tpay/access-token', [
            'client_id' => env('TBC_CLIENT_ID'),
            'client_secret' => env('TBC_CLIENT_SECRET'),
        ]);

        if ($response->successful()) {
            $tokenData = $response->json();
            return $tokenData['access_token'] ?? null;
        } else {
            Log::error('Failed to retrieve access token', ['response' => $response->json()]);
            return null;
        }
    }
}
