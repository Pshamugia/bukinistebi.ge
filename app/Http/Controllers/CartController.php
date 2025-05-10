<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the cart page.
     */
    public function index()
    {
        $cart = Auth::user()->cart()->with('cartItems.book.author')->first(); // Ensure you're getting a single cart instance

        $subtotal = 0;
        $shipping = 5.00; // Example fixed shipping cost

        if ($cart) {
            $subtotal = $cart->cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });
        }

        $total = $subtotal + $shipping;
        $isHomePage = false;
        return view('cart', compact('cart', 'subtotal', 'shipping', 'total', 'isHomePage'));
    }


    /**
     * Add a book to the cart.
     */
    public function add(Request $request, Book $book)
    {
        $cart = Auth::user()->cart()->firstOrCreate([
            'user_id' => Auth::id(),
        ]);

        $quantity = min($request->input('quantity', 1), $book->quantity); // Limit to max quantity available
        $cartItem = $cart->cartItems()->where('book_id', $book->id)->first();

        if ($cartItem) {
            $cartItem->quantity = min($cartItem->quantity + $quantity, $book->quantity); // Don't exceed max quantity
            $cartItem->save();
        } else {
            $cart->cartItems()->create([
                'book_id' => $book->id,
                'quantity' => $quantity,
                'price' => $book->price,
            ]);
        }

        // Store cart items in cookie for 1 day
        $bookIds = $cart->cartItems->pluck('book_id')->toArray();
        cookie()->queue(cookie('abandoned_cart', json_encode($bookIds), 1440)); // 1440 = 1 day

        return response()->json([
            'status' => 'added',
            'cartCount' => $cart->cartItems->count(),
        ]);
    }


    /**
     * Remove a book from the cart.
     */
    public function remove($bookId)
    {
        // Get the user's cart
        $cart = Auth::user()->cart;

        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'შენი კალათა ცარიელია.');
        }

        // Find the cart item by book ID and remove it
        $cartItem = $cart->cartItems()->where('book_id', $bookId)->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return redirect()->route('cart.index')->with('success', 'წიგნი წაშლილია კალათიდან.');
    }

    /**
     * Update the quantity of a book in the cart.
     */
    public function updateQuantity(Request $request)
    {
        $bookId = $request->input('book_id');
        $action = $request->input('action');

        $cartItem = CartItem::where('book_id', $bookId)
            ->where('cart_id', Auth::user()->cart->id)
            ->first();

        if (!$cartItem) {
            return response()->json(['success' => false, 'message' => 'Item not found in cart.']);
        }

        $book = Book::find($bookId);
        if ($action === 'increase' && $cartItem->quantity < $book->quantity) {
            $cartItem->quantity += 1;
        } elseif ($action === 'decrease' && $cartItem->quantity > 1) {
            $cartItem->quantity -= 1;
        }

        $cartItem->save();

        return response()->json(['success' => true]);
    }



    public function toggle(Request $request)
    {
        $bookId = $request->input('book_id');
        $quantity = $request->input('quantity', 1); // Default quantity to 1

        $user = Auth::user();
        $cart = $user->cart ?? $user->cart()->create(); // Ensure cart exists or create it

        // Verify that the book exists
        $book = Book::find($bookId);
        if (!$book) {
            return response()->json(['success' => false, 'message' => 'Book not found.']);
        }

        // Check if the book is already in the cart
        $cartItem = $cart->cartItems()->where('book_id', $bookId)->first();

        if ($cartItem) {
            // If it's in the cart, remove it
            $cartItem->delete();
            $action = 'removed';
        } else {
            // If it's not in the cart, add it with the specified quantity
            $cart->cartItems()->create([
                'book_id' => $bookId,
                'quantity' => $quantity,
                'price' => $book->price,
            ]);
            $action = 'added';
        }

        return response()->json([
            'success' => true,
            'action' => $action,
            'cart_count' => $cart->cartItems->count(),
        ]);
    }
}
