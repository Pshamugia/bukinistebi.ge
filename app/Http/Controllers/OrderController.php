<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index()
    {
        // Check if the user has any orders
        $orders = Auth::user()->orders()->with('orderItems.book')->paginate(10);

        // Handle case where there are no orders
        if ($orders->isEmpty()) {
            return view('orders', ['orders' => []])->with('info', 'You have no orders yet.');
        }

        return view('orders', compact('orders'));
    }

    /**
     * Show the form for creating a new order (Checkout).
     */
    public function create()
    {
        // Ensure the user has a cart
        $cart = Auth::user()->cart;

        // Handle case where the cart is empty
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal = $cart->cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        // Set shipping cost based on the city


        $city = session('city', 'No city selected');  // Default to 'No city selected' if not found in session

        $shipping = ($city === 'თბილისი') ? 5.00 : 7.00;

        $total = $subtotal + $shipping;


        return view('orders.create', compact('cart', 'subtotal', 'shipping', 'total', 'city'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        // Ensure the user has a cart
        $cart = Auth::user()->cart;

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal = $cart->cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $shipping = 10.00; // Fixed shipping cost; adjust as needed
        $total = $subtotal + $shipping;

        $order = Order::create([
            'user_id' => Auth::id(),
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
            'status' => 'pending',
            'city' => session('city'), // fallback from session
        ]);

        // Create order items based on the cart items
        foreach ($cart->cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'book_id' => $cartItem->book_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
            ]);
        }

        // Clear the user's cart after the order is created
        $cart->cartItems()->delete();
        $cart->delete();

        return redirect()->route('orders.index')->with('success', 'Your order has been placed successfully.');
    }

    /**
     * Display the specified order details.
     */
    public function show($id)
    {
        // Ensure the user has access to the order
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('orderItems.book')
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    /**
     * Cancel (delete) an order.
     */
    public function destroy($id)
    {
        // Ensure the user has access to the order
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Cancel the order by deleting it
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Your order has been cancelled.');
    }


    // OrderController.php
    public function checkout(Request $request)
    {
        // Validate user inputs
        $validatedData = $request->validate([
            'payment_method' => 'required|string',
            'name' => 'required|string|max:255',
            'phone' => ['required', 'digits:9'],
            'address' => 'required|string|max:255',
            'city' => 'required|string', // Validate the city field

        ]);

        session(['city' => $validatedData['city']]);

        // Ensure the user has a cart
        $cart = Auth::user()->cart;

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal = $cart->cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        // Dynamically set the shipping cost based on the selected city
        $shipping = ($validatedData['city'] === 'თბილისი') ? 5.00 : 7.00; // Tbilisi gets 5 Lari, others get 7 Lari
        $total = $subtotal + $shipping; // Total = subtotal + shipping


        // Check if there’s enough stock for each item in the cart
        foreach ($cart->cartItems as $cartItem) {
            $book = $cartItem->book;
            if ($book->quantity < $cartItem->quantity) {
                return back()->with('error', 'Not enough stock available for ' . $book->title);
            }
        }

        // Create the order
        $order = Order::create([
            'user_id' => Auth::id(),
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
            'status' => 'pending',
            'name' => $validatedData['name'],
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
            'city' => $validatedData['city'], // ✅ Save city to database
            'payment_method' => $validatedData['payment_method'],
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

        // Clear the user's cart
        $cart->cartItems()->delete();
        $cart->delete();

        // Redirect based on payment method
        if ($validatedData['payment_method'] === 'courier') {
            cookie()->queue(cookie()->forget('abandoned_cart'));

            return redirect()->route('order_courier', ['order' => $order->id])->with('success', 'Your order has been received. Pay with the courier.');
        }

        return redirect()->back()->with('success', 'Proceed with bank transfer.');
    }

    public function orderCourier($orderId, Request $request)
    {
        $order = Order::with('orderItems.book') // Load order items and their related books
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Pass the order data to the view
        $data = [
            'order' => $order,
        ];



        return view('order_courier', compact('order'));
    }


    public function purchaseHistory()
    {
        $userId = auth()->id(); // Get the authenticated user ID

        // Fetch orders for the authenticated user, ordered by latest first
        $orders = Order::with('orderItems') // Assuming you have a relationship defined
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc') // Order by creation date, latest first
            ->paginate(10); // Retrieve 10 orders per page 

        return view('purchase-history', compact('orders'));
    }

    public function status($id)
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();

        $statusKey = $order->status;
        $translatedStatus = Order::$statusesMap[$statusKey] ?? $statusKey;


        $status = new \stdClass();
        $status->key = $statusKey;
        $status->label = $translatedStatus;

        return view('order.status', [
            'status' => $status,
        ]);
    }
}
