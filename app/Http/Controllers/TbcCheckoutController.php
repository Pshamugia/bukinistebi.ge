<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Auction;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use App\Mail\OrderPurchased;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class TbcCheckoutController extends Controller
{
    public function initializePayment(Request $request)
    {

        // Validate user inputs
        $validatedData = $request->validate([
            'payment_method' => 'required|string',
            'name' => 'required|string|max:255',
            'phone' => ['required', 'digits:9'],
            'address' => 'required|string|max:255',
            'city' => 'required|string', // Validate the city field
        ]);

        // Ensure the user has a cart
        $cart = Auth::user()->cart;
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cart->cartItems->sum(fn ($item) => $item->price * $item->quantity);
        $shipping = ($validatedData['city'] === 'თბილისი') ? 5.00 : 7.00; // Tbilisi gets 5 Lari, others get 7 Lari
        $total = $subtotal + $shipping; // Total = subtotal + shipping

        session(['city' => $validatedData['city']]);

        // Create the order
        $order = Order::create([
            'user_id' => Auth::id(),
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
            'status' => 'pending',
            'order_id' => 'ORD-' . uniqid(),
            'payment_method' => $validatedData['payment_method'],
            'name' => $validatedData['name'],
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
            'city' => $validatedData['city'], // ✅ 
        ]);

        // Create order items
        foreach ($cart->cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'book_id' => $cartItem->book_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
            ]);
        }

        // dd(1);

        // Send the email based on the payment method
        if ($validatedData['payment_method'] === 'courier') {
            // Send Courier Payment Email
            Mail::to('pshamugia@gmail.com')->send(new OrderPurchased($order, 'courier')); // Send courier email

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

                if (count($quantityUpdateErrs) > 0) {
                    Log::info("Failed to update book quantity", [
                        'id' => $paymentId,
                        'failed_books' => json_encode($quantityUpdateErrs),
                    ]);
                }
                // ✅ Clear cart + forget cookie
                $cart->cartItems()->delete();
                $cart->delete();
                cookie()->queue(cookie()->forget('abandoned_cart'));
            }


            return redirect()->route('order_courier', ['orderId' => $order->id])
                ->with('success', 'Your order has been received. Pay with the courier.');
        } elseif ($validatedData['payment_method'] === 'bank_transfer') {
            // Send Bank Transfer Email
            //Mail::to('pshamugia@gmail.com')->send(new OrderPurchased($order, 'bank_transfer')); // Send bank transfer email
            return $this->processPayment($total, $order->order_id);  // Process payment for bank transfer
        }
    }




    public function initializeAuctionPayment(Request $request)
    {
        if ($request->has('auction_id')) {
            $auction = \App\Models\Auction::with('book')->find($request->auction_id);

            if (!$auction || $auction->winner_id !== Auth::id()) {
                return back()->with('error', 'Unauthorized auction payment.');
            }

            $paymentId = 'AUC-' . $auction->id . '-' . rand(1000, 9999);

            $payload = [
                'amount' => [
                    'currency' => 'GEL',
                    'total' => number_format($auction->current_price, 0, '.', ''), // real amount
                ],
                'returnurl' => 'https://bukinistebi.ge/tbc-callback',
                'description' => 'Auction Payment', // ≤ 30 characters!
                'merchantPaymentId' => $paymentId,
            ];

            $tokenResponse = Http::asForm()->withHeaders([
                'apikey' => 'rg9NENtqGGQHAvAVQ8wl7oePb8AjgPV5',
            ])->post('https://api.tbcbank.ge/v1/tpay/access-token', [
                'client_id' => '7002051',
                'client_secret' => '4fdynf4ExqNpuWXD',
            ]);

            $accessToken = $tokenResponse->json()['access_token'] ?? null;

            if (!$accessToken) {
                Log::error('❌ Auction token fetch failed', ['response' => $tokenResponse->json()]);
                return back()->with('error', 'Could not get token.');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'apikey' => 'rg9NENtqGGQHAvAVQ8wl7oePb8AjgPV5',
                'Content-Type' => 'application/json',
            ])->post('https://api.tbcbank.ge/v1/tpay/payments', $payload);

            if ($response->successful()) {
                return redirect($response['links'][1]['uri']);
            } else {
                Log::error('❌ Auction Payment Failed', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);
                return back()->with('error', 'Auction payment failed.');
            }
        }
    }



    public function processPayment($total, $orderId)
    {
        $amountInCents = strval($total);

        $payload = [
            'amount' => [
                'currency' => 'GEL',
                'total' => $amountInCents,
            ],
            'returnurl' => str_replace('http://', 'https://', route('order.status', ['id' => $orderId])),
            'callbackUrl' => str_replace('http://', 'https://', route('payment.callback.book')),
            'description' => 'Order from Bukinistebi',
            'merchantPaymentId' => $orderId,
        ];

        // Retrieve the new access token
        $accessToken = $this->getAccessToken(); // Ensure this method retrieves the latest token

        Log::info('Using access token for payment:', ['access_token' => $accessToken]);

        $tokenResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'apikey' => env('TBC_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.tbcbank.ge/v1/tpay/payments', $payload);

        if ($tokenResponse->successful()) {
            $response = $tokenResponse->json();

            $this->updateOrderRecord($orderId, $response["payId"]);


            return redirect($response['links'][1]['uri']);
        } else {
            Log::error('Payment processing failed', ['response' => $tokenResponse->json()]);
            return back()->with('error', 'Payment processing failed. Please try again.');
        }
    }

    protected function updateOrderRecord($orderID, $gate_order_id): bool
    {
        $set = [
            "gate_id" => $gate_order_id,
        ];

        try {
            DB::table("orders")->where("order_id", $orderID)->update($set);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }


    public function makePaymentRequest($total, $order_id)
    {
        try {
            // Retrieve access token
            $tokenResponse = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'apikey' => env('TBC_API_KEY'),
            ])->post(env('TBC_BASE_URL') . '/v1/tpay/access-token', [
                'client_id' => env('TBC_CLIENT_ID'),
                'client_secret' => env('TBC_CLIENT_SECRET'),
            ]);

            // Decode the token response
            $tokenData = $tokenResponse->json();  // Ensure this is parsed as an array
            if (!isset($tokenData['access_token'])) {
                Log::error('Failed to retrieve access token', ['response' => $tokenData]);
                return response()->json(['error' => 'Failed to retrieve access token'], 500);
            }

            $accessToken = $tokenData['access_token'];

            // Prepare the payload for the payment request
            $payload = [
                'amount' => [
                    'currency' => 'GEL',
                    'total' => strval($total * 100), // Convert to cents and ensure it’s a string
                ],
                'returnurl' => 'https://bukinistebi.ge/tbc-callback',
                'description' => 'Order from Bukinistebi',
                'merchantPaymentId' => $order_id,
            ];

            // Send the payment request
            $paymentResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'apikey' => env('TBC_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post(env('TBC_BASE_URL') . '/v1/tpay/payments', $payload);

            // Decode the payment response
            $paymentData = $paymentResponse->json();  // Decode response to an array

            if ($paymentResponse->status() !== 200) {
                Log::error('Payment initialization failed', ['response' => $paymentData]);
                return response()->json(['error' => 'Payment initialization failed'], 500);
            }

            return $paymentData; // Return payment details if successful

        } catch (\Exception $e) {
            Log::error('Payment process error', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'Payment process error'], 500);
        }
    }

    // public function handleCallback(Request $request)
    // {
    //     Log::info('📥 Callback received:', ['request' => $request->all()]);

    //     $payId = $request->query('payId'); // Use query string
    //     if (!$payId) {
    //         Log::warning('⚠️ No payId received. Trying fallback match based on latest pending order.');

    //         // Fallback: manually match the latest pending order
    //         $order = Order::where('status', 'pending')
    //             ->where('user_id', Auth::id()) // or use session()->get('user_id') if needed
    //             ->latest()
    //             ->first();

    //         if ($order) {
    //             $order->status = 'paid';
    //             $order->save();

    //             // Clear cart
    //             $cart = $order->user->cart;
    //             if ($cart) {
    //                 $cart->cartItems()->delete();
    //                 $cart->delete();


    //                 // ✅ Forget cart cookie
    //                 cookie()->queue(cookie()->forget('abandoned_cart'));
    //             }

    //             // Send email
    //             Mail::to('pshamugia@gmail.com')->send(new OrderPurchased($order, 'bank_transfer'));
    //             Log::info('✅ Fallback: Order marked paid and email sent. Order ID: ' . $order->order_id);

    //             return redirect()->route('order.success')->with('success', 'Payment completed (fallback method).');
    //         }

    //         Log::error('❌ Fallback failed: No pending order found.');
    //         return redirect()->route('order.failed')->with('error', 'Payment could not be verified.');
    //     }

    //     // 🔐 Token and verify from TBC
    //     $token = $this->getAccessToken();
    //     if (!$token) {
    //         Log::error('❌ Failed to retrieve access token.');
    //         return back()->withErrors(['message' => 'Failed to retrieve access token.']);
    //     }

    //     $response = Http::withHeaders([
    //         'Accept' => 'application/json',
    //         'apikey' => env('TBC_API_KEY'),
    //         'Authorization' => 'Bearer ' . $token,
    //     ])->get(env('TBC_BASE_URL') . '/v1/tpay/payments/' . $payId); // ✅ სწორი მეთოდი და URL

    //     Log::info('💳 Callback response:', ['status' => $response->status(), 'body' => $response->json()]);

    //     if ($response->successful()) {
    //         $paymentData = $response->json();

    //         // Match by order_id
    //         $merchantPaymentId = $paymentData['merchantPaymentId'] ?? null;
    //         // ✅ Handle auction payments

    //         // ✅ Auction Access Fee Payment (1₾)
    //         if (str_starts_with($merchantPaymentId, 'AUC-FEE-')) {
    //             $parts = explode('-', $merchantPaymentId);
    //             $userId = $parts[2] ?? null;
    //             $auctionId = $parts[3] ?? null;

    //             if ($userId && $auctionId) {
    //                 $user = \App\Models\User::find($userId);
    //                 if ($user && !$user->has_paid_auction_fee) {
    //                     $user->has_paid_auction_fee = true;
    //                     $user->save();

    //                     Log::info("✅ User {$userId} paid the 1₾ auction access fee for auction {$auctionId}");

    //                     // Force-authenticate to make sure session has correct user
    //                     if (!Auth::check() || Auth::id() !== $user->id) {
    //                         Auth::login($user);
    //                     }

    //                     return redirect()->route('auction.show', ['auction' => $auctionId])
    //                         ->with('success', 'საფასური გადახდილია, ახლა შეგიძლიათ ბიჯის გაკეთება.');
    //                 }
    //             }

    //             Log::warning("⚠️ Could not handle AUC-FEE callback correctly", compact('userId', 'auctionId'));
    //             return redirect()->route('home')->with('error', 'გადახდა განხორციელდა, მაგრამ აუქციონის დეტალები ვერ მოიძებნა.');
    //         }




    //         if (str_starts_with($merchantPaymentId, 'AUC-')) {
    //             $auctionId = str_replace('AUC-', '', $merchantPaymentId);
    //             $auction = Auction::find($auctionId);

    //             if ($auction && !$auction->is_paid && Auth::id() === $auction->winner_id) {
    //                 $auction->is_paid = true;
    //                 $auction->save();
    //                 Log::info("✅ Auction {$auction->id} marked as paid.");
    //                 return redirect()->route('auction.show', $auction->id)->with('success', 'Auction payment successful.');
    //             }
    //         }


    //         if (!$merchantPaymentId) {
    //             Log::error('❌ Missing merchantPaymentId from response');
    //             return redirect()->route('order.failed')->with('error', 'Payment verified but order reference missing.');
    //         }

    //         $order = Order::where('order_id', $merchantPaymentId)->first();
    //         if ($order && $order->status !== 'paid') {
    //             $order->status = 'paid';
    //             $order->save();

    //             // 🔥 Reduce stock only for direct pay
    //             if (str_starts_with($order->order_id, 'ORD-DIRECT-')) {
    //                 foreach ($order->orderItems as $item) {
    //                     $book = $item->book;
    //                     if ($book && $book->quantity >= $item->quantity) {
    //                         $book->quantity -= $item->quantity;
    //                         $book->save();
    //                     }
    //                 }
    //             }

    //             // Optional: clear cart only if not direct pay
    //             if (str_starts_with($order->order_id, 'ORD-') && !str_starts_with($order->order_id, 'ORD-DIRECT-')) {
    //                 $cart = $order->user->cart;
    //                 if ($cart) {
    //                     $cart->cartItems()->delete();
    //                     $cart->delete();
    //                     cookie()->queue(cookie()->forget('abandoned_cart'));
    //                 }
    //             }

    //             Mail::to('pshamugia@gmail.com')->send(new OrderPurchased($order, 'bank_transfer'));
    //         }

    //         return redirect()->route('order.success')->with('success', 'Payment completed.');
    //     }

    //     Log::error('❌ Payment not approved', ['response' => $response->json()]);
    //     return redirect()->route('order.failed')->with('error', 'Payment failed or was canceled.');
    // }








    public function show($orderId)
    {
        $order = Order::where('order_id', $orderId)->first();

        if (!$order) {
            return redirect()->route('error.page')->with('message', 'Order not found.');
        }

        // Pass the city from the session to the view
        $city = session('city', 'No city selected');

        // Optionally, clear the session value after use
        session()->forget('city'); // Ensure the city is removed after order is confirmed

        return view('tbc-checkout', compact('order', 'city'));
    }


    private function getAccessToken()
    {
        $response = Http::asForm()->withHeaders([
            'apikey' => env('TBC_API_KEY'), // Ensure this is correct
        ])->post('https://api.tbcbank.ge/v1/tpay/access-token', [
            'client_id' => env('TBC_CLIENT_ID'), // Ensure this is correct
            'client_secret' => env('TBC_CLIENT_SECRET'), // Ensure this is correct
        ]);

        // Check if the response is successful and contains the access token
        if ($response->successful()) {
            $tokenData = $response->json();

            return $tokenData['access_token'] ?? null; // Return the access token or null if not set
        } else {
            Log::error('Failed to retrieve access token', ['response' => $response->json()]);
            return null; // Return null if the token retrieval fails
        }
    }

    public function payAuctionFee(Request $request)
    {

        $user = Auth::user();
        $auctionId = $request->input('auction_id');

        if ($user->paidAuction($auctionId)) {
            return back()->with('success', 'You have already paid for this auction.');
        }

        if (!$user->startedAuctionPayment($auctionId)) {
            $user->paidAuctions()->attach($auctionId, [
                'paid_at' => null,
            ]);
        }

        $paymentId = 'AUC-FEE-' . $user->id . '-' . $auctionId . '-' . uniqid();

        $payload = [
            'amount' => [
                'currency' => 'GEL',
                'total' => '1',
            ],
            'callbackUrl' => str_replace('http://', 'https://', route('payment.callback')),
            'returnurl' => str_replace('http://', 'https://', url()->previous()),
            'description' => 'Auction Access Fee',
            'merchantPaymentId' => $paymentId,
        ];

        $token = $this->getAccessToken();

        if (!$token) {
            Log::error('❌ Token fetch failed for auction fee');
            return back()->with('error', 'Failed to get token.');
        }


        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'apikey' => env('TBC_API_KEY'),
            'Content-Type' => 'application/json',
        ])->withBody(json_encode($payload), 'application/json')
            ->post("https://api.tbcbank.ge/v1/tpay/payments");

        if ($response->successful()) {
            DB::table('auction_users')
                ->where('user_id', $user->id)
                ->where('auction_id', $auctionId)
                ->update(['updated_at' => now()]);

            return redirect($response['links'][1]['uri']);
        } else {
            Log::info('TBC payment request fail', [
                'status' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json(),
            ]);
        }

        session(['auction_id' => $auctionId]);


        Log::error('❌ Auction fee payment failed', ['response' => $response->json()]);
        return back()->with('error', 'Auction fee payment failed.');
    }



    // direct payment option for full blade

    public function directPayFromBook(Request $request)
{
    $validatedData = $request->validate([
        'book_id' => 'required|exists:books,id',
        'quantity' => 'required|integer|min:1',
        'payment_method' => 'required|string',
        'name' => 'required|string|max:255',
        'phone' => ['required', 'digits:9'],
        'address' => 'required|string|max:255',
        'city' => 'required|string',
    ]);

    $book = \App\Models\Book::findOrFail($validatedData['book_id']);
    $quantity = $validatedData['quantity'];
    $subtotal = $book->price * $quantity;
    $shipping = ($validatedData['city'] === 'თბილისი') ? 5.00 : 7.00;
    $total = $subtotal + $shipping;

    // ✅ Create the order FIRST for both payment methods
    $order = Order::create([
        'user_id' => Auth::id(),
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'total' => $total,
        'status' => 'pending',  // Will change to 'processing' if courier
        'order_id' => 'ORD-DIRECT-' . uniqid(),
        'payment_method' => $validatedData['payment_method'],
        'name' => $validatedData['name'],
        'phone' => '+995' . $validatedData['phone'],
        'address' => $validatedData['address'],
        'city' => $validatedData['city'],
    ]);

    // ✅ Attach the order item
    OrderItem::create([
        'order_id' => $order->id,
        'book_id' => $book->id,
        'quantity' => $quantity,
        'price' => $book->price,
    ]);

    // 🚫 For bank transfer we WAIT for callback to reduce stock
    // ✅ For courier we reduce stock immediately
    if ($validatedData['payment_method'] === 'courier') {

        // ✅ Reduce stock now
        if ($book->quantity >= $quantity) {
            $book->quantity -= $quantity;
            $book->save();
        }

        // ✅ Change order status to processing (not pending)
        $order->status = 'processing';
        $order->save();

        // ✅ Send confirmation email
        Mail::to('pshamugia@gmail.com')->send(new OrderPurchased($order, 'courier'));

        // ✅ Redirect to courier confirmation
   // ✅ Redirect guests and logged users safely
   if (Auth::check()) {
    return redirect()->route('order_courier', ['orderId' => $order->id])
        ->with('success', 'შეკვეთა მიღებულია. გადაიხდით კურიერთან.');
} else {
    return redirect()->route('guest.order.success', ['orderId' => $order->id])
        ->with('success', 'შეკვეთა მიღებულია. გადაიხდით კურიერთან.');
}
    }

    // ✅ If bank transfer: send to payment processor
    if ($validatedData['payment_method'] === 'bank_transfer') {
        return $this->processPayment($total, $order->order_id);
    }

    return back()->with('error', 'Payment method not recognized.');
}

}
