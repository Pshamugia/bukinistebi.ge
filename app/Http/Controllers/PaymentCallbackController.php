<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\OrderPurchased;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

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

        $order = Order::where('gate_id', $paymentId)->firstOrFail();

        if (!$order) {
            Log::info("Can't find order");
        }


        // 🔥 Reduce stock only for direct pay
        if (str_starts_with($order->order_id, 'ORD-DIRECT-') && $paymentStatus == "Succeeded") {
            Log::info("payment status is: " .  $paymentStatus);
            foreach ($order->orderItems as $item) {
                Log::info("in loop direct cb");
                $book = $item->book;
                if ($book && $book->quantity >= $item->quantity) {
                    $book->quantity -= $item->quantity;
                    $book->save();
                }
            }
        }

        $order->status = $paymentStatus ?? 'Pending';
        $order->save();



        if ($paymentStatus == "Succeeded") {
            if (str_starts_with($order->order_id, 'ORD-DIRECT-') == false) {
                $cart = $order->user->cart;
                Log::info("authed code runnin");
                

                if ($cart) {
                    $quantityUpdateErrs = [];
                    foreach ($cart->cartItems as $cartItem) {
                        $book = $cartItem->book;
                        if ($book->quantity >= $cartItem->quantity) {
                            $book->quantity -= $cartItem->quantity;
                            $book->save(); // Save updated quantity
                        } else {
                            $quantityUpdateErrs = $book->id;
                        }
                    }
    
                    $cart->cartItems()->delete();
                    $cart->delete();
    
                    if (count($quantityUpdateErrs) > 0) {
                        Log::info("Failed to update book quantity", [
                            'id' => $paymentId,
                            'failed_books' => json_encode($quantityUpdateErrs),
                        ]);
                    }
                }
            }
        }

        Mail::to('pshamugia@gmail.com')->send(new OrderPurchased($order, 'bank_transfer'));
        Log::info('Got payment callback: ', [
            'id' => $paymentId,
            'status' => $paymentStatus,
        ]);


        return response()->json(['status' => 'ok'], 200);
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
