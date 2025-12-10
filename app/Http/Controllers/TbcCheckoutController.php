<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Bundle;
use App\Models\Auction;
use App\Models\OrderItem;
use App\Mail\OrderInvoice;
use Illuminate\Support\Str;
use App\Mail\OrderPurchased;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;


class TbcCheckoutController extends Controller
{
    public function initializePayment(Request $request)
    {
        // Validate user-level checkout fields
        $validated = $request->validate([
            'payment_method' => 'required|string|in:courier,bank_transfer',
            'name'           => 'required|string|max:255',
            'phone'          => ['required', 'digits:9'],
            'address'        => 'required|string|max:255',
            'city'           => 'required|string',
        ]);

        // Load cart with items, each item with book + genres (to detect Souvenirs)
        $cart = Auth::user()->cart()
            ->with(['cartItems.book.genres', 'cartItems.bundle.books'])
            ->first();


        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Validate sizes for Souvenirs and compute subtotal
        $subtotal = 0;
        foreach ($cart->cartItems as $item) {
            $subtotal += ($item->price * $item->quantity);

            // Souvenir validation applies only to single-book lines
            if ($item->book_id && $item->book) {
                $book = $item->book;

                $isSouvenir = $book->genres->contains(function ($g) {
                    return ($g->name ?? '') === 'სუვენირები' || ($g->name_en ?? '') === 'Souvenirs';
                });

                if ($isSouvenir) {
                    if (empty($item->size)) {
                        return back()->withErrors([
                            'size' => "გთხოვთ აირჩიოთ ზომა '{$book->title}'-სთვის."
                        ])->withInput();
                    }
                    if (!in_array($item->size, ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'], true)) {
                        return back()->withErrors([
                            'size' => "არასწორი ზომა '{$book->title}'-სთვის."
                        ])->withInput();
                    }
                }
            }
        }


        $shipping = ($validated['city'] === 'თბილისი') ? 5.00 : 7.00;
        $total    = $subtotal + $shipping;

        // Persist city for the checkout page (your existing behavior)
        session(['city' => $validated['city']]);

        // Create order
        $order = Order::create([
            'user_id'        => Auth::id(),
            'subtotal'       => $subtotal,
            'shipping'       => $shipping,
            'total'          => $total,
            'status'         => 'pending',
            'order_id'       => 'ORD-' . uniqid(),
            'payment_method' => $validated['payment_method'],
            'name'           => $validated['name'],
            'phone'          => $validated['phone'],          // keep your original format
            'email'          => optional(Auth::user())->email, // NEW
            'address'        => $validated['address'],
            'city'           => $validated['city'],
        ]);

        // Create order items (write size from cart_items.size)
        foreach ($cart->cartItems as $cartItem) {
            if ($cartItem->bundle_id) {
                // Bundle line
                OrderItem::create([
                    'order_id'  => $order->id,
                    'bundle_id' => $cartItem->bundle_id,
                    'book_id'   => null,
                    'quantity'  => $cartItem->quantity,
                    'price'     => $cartItem->price,      // bundle unit price
                    // 'meta'   => $cartItem->meta,       // if you store any JSON
                ]);
            } else {
                // Single-book line (keep your size support)
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id'  => $cartItem->book_id,
                    'quantity' => $cartItem->quantity,
                    'price'    => $cartItem->price,
                    'size'     => $cartItem->size ?? null,
                ]);
            }
        }


        // Branch by payment method
        if ($validated['payment_method'] === 'courier') {
            $quantityUpdateErrs = [];

            foreach ($cart->cartItems as $cartItem) {
                if ($cartItem->bundle_id && $cartItem->bundle) {
                    // Decrement each member book by (qty per bundle * number of bundles)
                    $bundle = $cartItem->bundle->load('books');
                    foreach ($bundle->books as $b) {
                        $needPerBundle = max(1, (int)$b->pivot->qty);
                        $totalNeed     = $needPerBundle * (int)$cartItem->quantity;

                        $book = \App\Models\Book::find($b->id);
                        if ($book && $book->quantity >= $totalNeed) {
                            $book->quantity -= $totalNeed;
                            $book->save();
                        } else {
                            $quantityUpdateErrs[] = $b->id;
                        }
                    }
                } else {
                    // Single-book item
                    $book = $cartItem->book;
                    if ($book && $book->quantity >= $cartItem->quantity) {
                        $book->quantity -= $cartItem->quantity;
                        $book->save();
                    } else {
                        if ($book) $quantityUpdateErrs[] = $book->id;
                    }
                }
            }

            if (!empty($quantityUpdateErrs)) {
                Log::info("Failed to update book quantity", ['failed_books' => $quantityUpdateErrs]);
            }

            // Mark order as processing and clear cart (keep your code)
            $order->status = 'processing';
            $order->save();

            $cart->cartItems()->delete();
            $cart->delete();
            cookie()->queue(cookie()->forget('abandoned_cart'));

            $customerEmail = optional(Auth::user())->email;

            Mail::to('pshamugia@gmail.com')->send(new OrderPurchased($order, 'courier'));

            // Send customer invoice if logged in
            if ($customerEmail) {
                Mail::to($customerEmail)->send(new OrderInvoice($order));
            }

            return redirect()->route('order_courier', ['orderId' => $order->id])
                ->with('success', 'Your order has been received. Pay with the courier.');
        }


        // Bank transfer: do NOT reduce stock / clear cart here — do it after TBC callback
        if ($validated['payment_method'] === 'bank_transfer') {
            return $this->processPayment($total, $order->order_id);
        }

        return back()->with('error', 'Payment method not recognized.');
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
            'book_id'        => 'required|exists:books,id',
            'quantity'       => 'required|integer|min:1',
            'payment_method' => 'required|string|in:bank_transfer,courier',
            'name'           => 'required|string|max:255',
            'phone'          => ['required', 'digits:9'],
            'email'          => 'nullable|email', // NEW
            'address'        => 'required|string|max:255',
            'city'           => 'required|string',
            'size'           => 'nullable|string|in:XS,S,M,L,XL,XXL,XXXL', // 👈 add size validation
        ]);

        $book = \App\Models\Book::with('genres')->findOrFail($validatedData['book_id']);
        $quantity = $validatedData['quantity'];
        $subtotal = $book->price * $quantity;
        $shipping = ($validatedData['city'] === 'თბილისი') ? 5.00 : 7.00;
        $total = $subtotal + $shipping;

        // 👀 If book is a Souvenir, enforce size
        $isSouvenir = $book->genres->contains(function ($g) {
            return ($g->name ?? '') === 'სუვენირები' || ($g->name_en ?? '') === 'Souvenirs';
        });

        if ($isSouvenir && empty($validatedData['size'])) {
            return back()->withErrors(['size' => 'გთხოვთ აირჩიოთ ზომა'])->withInput();
        }

        $customerEmail = optional(Auth::user())->email ?? ($validatedData['email'] ?? null);


        // ✅ Create the order
        $order = Order::create([
            'user_id'       => Auth::id(),
            'subtotal'      => $subtotal,
            'shipping'      => $shipping,
            'total'         => $total,
            'status'        => 'pending', // default
            'order_id'      => 'ORD-DIRECT-' . uniqid(),
            'payment_method' => $validatedData['payment_method'],
            'name'          => $validatedData['name'],
            'phone'         => '+995' . $validatedData['phone'],
            'email'         => $customerEmail, // NEW
            'address'       => $validatedData['address'],
            'city'          => $validatedData['city'],
        ]);

        // ✅ Attach the order item (including size if exists)
        OrderItem::create([
            'order_id' => $order->id,
            'book_id'  => $book->id,
            'quantity' => $quantity,
            'price'    => $book->price,
            'size'     => $validatedData['size'] ?? null, // 👈 store size in order_items
        ]);

        // 🚫 Bank transfer waits for callback to reduce stock
        // ✅ Courier reduces immediately
        if ($validatedData['payment_method'] === 'courier') {

            if ($book->quantity >= $quantity) {
                $book->quantity -= $quantity;
                $book->save();
            }


            // Reduce stock for any bundle items on paid orders (bank transfer path)
            $order->loadMissing('orderItems.bundle.books');
            foreach ($order->orderItems as $it) {
                if ($it->bundle_id && $it->bundle) {
                    foreach ($it->bundle->books as $b) {
                        $need = (int)$b->pivot->qty * (int)$it->quantity;
                        if ($need > 0 && $b->quantity >= $need) {
                            $b->decrement('quantity', $need);
                        }
                    }
                }
            }


            $order->status = 'processing';
            $order->save();

            // ✅ Send confirmation email with size included
            Mail::to('pshamugia@gmail.com')->send(new OrderPurchased($order, 'courier'));

            // Send customer invoice (auth or guest) if we have email
            if ($order->email) {
                Mail::to($order->email)->send(new OrderInvoice($order));
            }



            if (Auth::check()) {
                return redirect()->route('order_courier', ['orderId' => $order->id])
                    ->with('success', 'შეკვეთა მიღებულია. გადაიხდით კურიერთან.');
            } else {
                return redirect()->route('guest.order.success', ['orderId' => $order->id])
                    ->with('success', 'შეკვეთა მიღებულია. გადაიხდით კურიერთან.');
            }
        }

        if ($validatedData['payment_method'] === 'bank_transfer') {
            return $this->processPayment($total, $order->order_id);
        }

        return back()->with('error', 'Payment method not recognized.');
    }





    public function directPayBundle(Request $request, Bundle $bundle)
    {
          $token = $request->submission_token;

    // prevent duplicate
    if (session()->has("used_token_$token")) {
        return back()->with('warning', 'თქვენ უკვე გააგზავნეთ შეკვეთა.');
    }

    session()->put("used_token_$token", true);
    
        $data = $request->validate([
            'payment_method' => 'required|in:bank_transfer,courier',
            'name'           => 'required|string|max:255',
            'phone'          => ['required', 'regex:/^5\d{8}$/'],  // 9 digits starting with 5
            'city'           => 'required|string',
            'address'        => 'required|string|max:255',
            'quantity'       => 'required|integer|min:1',
            // if you added an email field in the form:
            'email'          => Auth::check() ? 'nullable|email' : 'nullable|email',
        ]);

        $customerEmail = optional(Auth::user())->email ?? ($data['email'] ?? null);


        // 1) Stock check via bundle->availableQuantity()
        $max = (int) $bundle->availableQuantity();
        $qty = min((int)$data['quantity'], $max);
        if ($qty < 1) {
            return back()->withErrors(['bundle' => __('This bundle is out of stock.')])->withInput();
        }

        // 2) Totals (same pattern as book)
        $subtotal = $bundle->price * $qty;
        $shipping = ($data['city'] === 'თბილისი') ? 5.00 : 7.00;
        $total    = $subtotal + $shipping;

        // 3) Satisfy NOT NULL user_id without schema changes
        $userId = Auth::id();
        if (!$userId) {
            $guest = User::firstOrCreate(
                ['email' => 'guest@bukinistebi.ge'],
                ['name' => 'Guest', 'password' => bcrypt(Str::random(16))]
            );
            $userId = $guest->id;
        }

        // 4) Create the order (include required columns)
        $order = Order::create([
            'user_id'        => $userId,
            'order_id'       => 'ORD-DIRECT-' . uniqid(),
            'name'           => $data['name'],
            'phone'          => '+995' . preg_replace('/\D/', '', $data['phone']),
            'email'          => $customerEmail, // NEW
            'city'           => $data['city'],
            'address'        => $data['address'],
            'subtotal'       => $subtotal,
            'shipping'       => $shipping,
            'total'          => $total,
            'status'         => 'pending',
            'payment_method' => $data['payment_method'],
        ]);

        // 5) Add the bundle order-item
        OrderItem::create([
            'order_id'  => $order->id,
            'bundle_id' => $bundle->id,
            'book_id'   => null,
            'quantity'  => $qty,
            'price'     => $bundle->price, // unit price
        ]);

        // Optional: customer email for invoice
        $customerEmail = Auth::user()->email ?? ($data['email'] ?? null);

        // 6) Branch like your book method
        if ($data['payment_method'] === 'courier') {
            // Reduce stock now (for courier)
            $bundle->load('books');
            foreach ($bundle->books as $b) {
                $need = (int)$b->pivot->qty * $qty;
                if ($need > 0) {
                    if ($b->quantity < $need) {
                        return back()->withErrors(['bundle' => __('Sorry, the bundle just went out of stock.')]);
                    }
                    $b->decrement('quantity', $need);
                }
            }

            $order->update(['status' => 'processing']);

            // Admin notification
 
            // Send admin notification (as you do)
            Mail::to('pshamugia@gmail.com')->send(new OrderPurchased($order, 'courier'));

             if ($order->email) {
                Mail::to($order->email)->send(new OrderInvoice($order));
            }
            

            // (optional) Customer invoice
            // if ($customerEmail) {
            //     Mail::to($customerEmail)->send(new OrderReceipt($order));
            // }

            return redirect()->route('order_courier', ['orderId' => $order->id])
                ->with('success', 'შეკვეთა მიღებულია. გადაიხდით კურიერთან.');
        }

        // BANK TRANSFER → do NOT reduce stock yet. TBC will call your callback.
        if ($customerEmail) {
            // stash for callback if user is guest
            session(['checkout_email' => $customerEmail]);
        }
        if (!Auth::check() && $order->email) {
            session(['checkout_email' => $order->email]);
        }
        
        return $this->processPayment($total, $order->order_id);
    }
}
