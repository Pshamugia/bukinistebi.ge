<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Bundle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    /**
     * Display the cart page.
     */
    public function index()
    {
        $cart = Auth::user()->cart()
            ->with([
                'cartItems.book.author',
                'cartItems.bundle.books' => function ($q) {
                    $q->select('books.id', 'title', 'quantity');
                },
            ])->first();

        $subtotal = 0;
        $shipping = 5;

        // dd(1);
        if ($cart) {
            // dump("cart exists");
            // attach a max_qty to each line (book or bundle)
            foreach ($cart->cartItems as $ci) {
           
                if ($ci->bundle_id && $ci->bundle) {
                        // dd($ci->bundle->books);
                    // MIN( floor(books.quantity / max(1, pivot.qty)) )
                    $ci->max_qty = (int) (DB::table('bundle_book')
                        ->join('books', 'books.id', '=', 'bundle_book.book_id')
                        ->where('bundle_book.bundle_id', $ci->bundle_id)
                        ->selectRaw(
                            'COALESCE(MIN(FLOOR(books.quantity / NULLIF(COALESCE(bundle_book.qty,1),0))),0) as maxqty'
                        )
                        ->value('maxqty') ?? 0);
                } elseif ($ci->book) {
                    $ci->max_qty = (int) ($ci->book->quantity ?? 0);
                } else {
                    $ci->max_qty = 0;
                }
            }

            $subtotal = $cart->cartItems->sum(fn ($i) => (int)$i->price * (int)$i->quantity);
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
        $cart = auth()->user()->cart()
            ->with([
                'cartItems.book.author',
                'cartItems.bundle.books' // includes pivot->qty
            ])->first();


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




    public function removeBundle(\App\Models\Bundle $bundle)
    {
        $cart = auth()->user()->cart;
        if ($cart) {
            $cart->cartItems()->where('bundle_id', $bundle->id)->delete();
        }
        return back()->with('success', 'Bundle removed from cart.');
    }



    public function clear()
    {
        $user = auth()->user();

        if ($user && $user->cart) {
            $user->cart->cartItems()->delete(); // delete all items
        }

        return redirect()->route('cart.index')->with('success', 'კალათა გასუფთავდა!');
    }




    /**
     * Update the quantity of a book in the cart.
     */
    public function updateQuantity(Request $request)
    {
        $request->validate([
            'action'    => 'required|in:increase,decrease',
            'book_id'   => 'nullable|integer',
            'bundle_id' => 'nullable|integer',
        ]);

        $cart = Auth::user()->cart;
        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'No cart found.']);
        }

        // Decide whether we’re updating a book line or a bundle line
        if ($request->filled('bundle_id')) {
            // ---- Bundle row ----
            $item = CartItem::where('cart_id', $cart->id)
                ->where('bundle_id', $request->bundle_id)
                ->first();

            if (!$item) {
                return response()->json(['success' => false, 'message' => 'Bundle item not found.']);
            }

            $bundle = Bundle::with('books')->find($request->bundle_id);
            if (!$bundle) {
                return response()->json(['success' => false, 'message' => 'Bundle not found.']);
            }

            $maxStock = $bundle->availableQuantity(); // how many full bundles can be made

        } else {
            // ---- Single book row ----
            $bookId = $request->input('book_id');

            $item = CartItem::where('cart_id', $cart->id)
                ->where('book_id', $bookId)
                ->first();

            if (!$item) {
                return response()->json(['success' => false, 'message' => 'Item not found.']);
            }

            $book = Book::find($bookId);
            $maxStock = $book?->quantity ?? 0;
        }

        // Adjust quantity with caps
        $qty = (int)$item->quantity;
        if ($request->action === 'increase') {
            $qty = min($qty + 1, max(0, (int)$maxStock));
        } else {
            $qty = max(1, $qty - 1);
        }

        $item->update(['quantity' => $qty]);

        // Recompute totals
        $cart->load('cartItems');
        $updatedTotal = (int)$item->price * (int)$item->quantity;
        $cartTotal    = $cart->cartItems->sum(fn ($i) => (int)$i->price * (int)$i->quantity);

        return response()->json([
            'success'      => true,
            'newQuantity'  => $item->quantity,
            'updatedTotal' => $updatedTotal,
            'cartTotal'    => $cartTotal + 5,  // keep your current shipping add-on
            'bookStock'    => isset($book) ? ($book->quantity ?? 0) : $maxStock, // for UI messages
        ]);
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



    public function addBundle(Bundle $bundle)
    {
        $bundle->load('books');

        if ($bundle->availableQuantity() < 1) {
            return back()->withErrors(['bundle' => __('This bundle is currently out of stock.')]);
        }

        $user = Auth::user();

        // Ensure the user has a cart
        $cart = $user->cart ?? $user->cart()->create(); // assumes User hasOne Cart

        // Try to find existing line for this bundle
        $item = $cart->cartItems()
            ->whereNull('book_id')
            ->where('bundle_id', $bundle->id)
            ->first();

        if (!$item) {

            $data_set = [
                'book_id'   => null,
                'bundle_id' => $bundle->id,
                'quantity'  => 1,
                'price'     => (int)$bundle->price, // store unit price
                'meta'      => [
                    'original_price' => (int)$bundle->original_price,
                    'savings'        => (int)$bundle->savings,
                ],
            ];

            $cart->cartItems()->create($data_set);
        } else {
            $newQty = min($item->quantity + 1, $bundle->availableQuantity());
            $item->update(['quantity' => $newQty]);
        }

        return back()->with('success', __('Bundle added to cart.'));
    }


    public function toggleBundle(Request $request)
{
    $request->validate([
        'bundle_id' => 'required|integer|exists:bundles,id',
    ]);

    $user = Auth::user();
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $bundle = \App\Models\Bundle::with('books')->findOrFail($request->bundle_id);

    // Ensure the user has a cart
    $cart = $user->cart ?? $user->cart()->create();

    // Find line for this bundle
    $item = $cart->cartItems()
        ->whereNull('book_id')
        ->where('bundle_id', $bundle->id)
        ->first();

    if ($item) {
        $item->delete();
        $action = 'removed';
    } else {
        // respect stock for the full bundle
        if ($bundle->availableQuantity() < 1) {
            return response()->json(['success' => false, 'message' => __('This bundle is out of stock.')], 409);
        }

        $cart->cartItems()->create([
            'book_id'   => null,
            'bundle_id' => $bundle->id,
            'quantity'  => 1,
            'price'     => (int) $bundle->price,
            'meta'      => [
                'original_price' => (int) $bundle->original_price,
                'savings'        => (int) $bundle->savings,
            ],
        ]);
        $action = 'added';
    }

    return response()->json([
        'success'     => true,
        'action'      => $action,
        'cart_count'  => $cart->cartItems()->count(),
    ]);
}

}
