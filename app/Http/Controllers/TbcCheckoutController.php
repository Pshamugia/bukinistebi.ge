<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Mail\OrderPurchased;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class TbcCheckoutController extends Controller
{
    public function initializePayment(Request $request)
    {
        // Validate user inputs
        $validatedData = $request->validate([
            'payment_method' => 'required|string',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
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
        ]);

        // Create order items
        foreach ($cart->cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'book_id' => $cartItem->book_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
            ]);

            $book = $cartItem->book;
            if ($book->quantity >= $cartItem->quantity) {
                $book->quantity -= $cartItem->quantity;
                $book->save(); // Save updated quantity
            } else {
                return back()->with('error', 'Not enough stock available.');
            }
        }

        // Send the email based on the payment method
        if ($validatedData['payment_method'] === 'courier') {


            // ✅ Clear cart + forget cookie
            $cart->cartItems()->delete();
            $cart->delete();
            cookie()->queue(cookie()->forget('abandoned_cart'));

            // Send Courier Payment Email
            Mail::to('pshamugia@gmail.com')->send(new OrderPurchased($order, 'courier')); // Send courier email
            $cart->cartItems()->delete();
            $cart->delete();
            return redirect()->route('order_courier', ['orderId' => $order->id])
                ->with('success', 'Your order has been received. Pay with the courier.');
        } elseif ($validatedData['payment_method'] === 'bank_transfer') {
            // Send Bank Transfer Email
            //Mail::to('pshamugia@gmail.com')->send(new OrderPurchased($order, 'bank_transfer')); // Send bank transfer email
            return $this->processPayment($total, $order->order_id);  // Process payment for bank transfer
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
            'returnurl' => 'https://bukinistebi.ge/tbc-callback',
            'description' => 'Order from Bukinistebi',
            'merchantPaymentId' => $orderId,
        ];


        // Clear the cart items after payment is successful
        $cart = Auth::user()->cart;
        if ($cart) {
            $cart->cartItems()->delete(); // Remove all items from the cart
            $cart->delete(); // Remove the cart itself
        }

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
            return redirect($response['links'][1]['uri']);
        } else {
            Log::error('Payment processing failed', ['response' => $tokenResponse->json()]);
            return back()->with('error', 'Payment processing failed. Please try again.');
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








    public function handleCallback(Request $request)
    {
        Log::info('📥 Callback received:', ['request' => $request->all()]);

        $payId = $request->query('payId'); // Use query string
        if (!$payId) {
            Log::warning('⚠️ No payId received. Trying fallback match based on latest pending order.');

            // Fallback: manually match the latest pending order
            $order = Order::where('status', 'pending')
                ->where('user_id', Auth::id()) // or use session()->get('user_id') if needed
                ->latest()
                ->first();

            if ($order) {
                $order->status = 'paid';
                $order->save();

                // Clear cart
                $cart = $order->user->cart;
                if ($cart) {
                    $cart->cartItems()->delete();
                    $cart->delete();

                    
        // ✅ Forget cart cookie
        cookie()->queue(cookie()->forget('abandoned_cart'));
                }

                // Send email
                Mail::to('pshamugia@gmail.com')->send(new OrderPurchased($order, 'bank_transfer'));
                Log::info('✅ Fallback: Order marked paid and email sent. Order ID: ' . $order->order_id);

                return redirect()->route('order.success')->with('success', 'Payment completed (fallback method).');
            }

            Log::error('❌ Fallback failed: No pending order found.');
            return redirect()->route('order.failed')->with('error', 'Payment could not be verified.');
        }

        // 🔐 Token and verify from TBC
        $token = $this->getAccessToken();
        if (!$token) {
            Log::error('❌ Failed to retrieve access token.');
            return back()->withErrors(['message' => 'Failed to retrieve access token.']);
        }

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'apikey' => env('TBC_API_KEY'),
            'Authorization' => 'Bearer ' . $token,
        ])->get(env('TBC_BASE_URL') . '/v1/tpay/payments/' . $payId);

        Log::info('💳 Callback response:', ['status' => $response->status(), 'body' => $response->json()]);

        if ($response->successful()) {
            $paymentData = $response->json();

            // Match by order_id
            $merchantPaymentId = $paymentData['merchantPaymentId'] ?? null;
            if (!$merchantPaymentId) {
                Log::error('❌ Missing merchantPaymentId from response');
                return redirect()->route('order.failed')->with('error', 'Payment verified but order reference missing.');
            }

            $order = Order::where('order_id', $merchantPaymentId)->first();
            if ($order && $order->status !== 'paid') {
                $order->status = 'paid';
                $order->save();

                // Clear cart
                $cart = $order->user->cart;
                if ($cart) {
                    $cart->cartItems()->delete();
                    $cart->delete();
                }

                // Send email
                Mail::to('pshamugia@gmail.com')->send(new OrderPurchased($order, 'bank_transfer'));
                Log::info('✅ Payment approved: Order marked paid and email sent.');
            }

            return redirect()->route('order.success')->with('success', 'Payment completed.');
        }

        Log::error('❌ Payment not approved', ['response' => $response->json()]);
        return redirect()->route('order.failed')->with('error', 'Payment failed or was canceled.');
    }








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
}
