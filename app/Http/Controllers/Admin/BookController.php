<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Author;
use App\Models\BookNews;
use App\Models\Category;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Mail\SubscriptionNotification;
use Illuminate\Support\Facades\Storage;
use App\Models\User; // Include the User model
use App\Models\Order; // Make sure to include your Order model
use Intervention\Image\Facades\Image; // Add this at the top of your controller


class BookController extends Controller
{
    public function index(Request $request)
    {
        // Get the quantity filter from the request (default is all quantities)
        $quantityFilter = $request->input('quantity', '');

        // Start the query for books
        $query = Book::orderBy('id', 'DESC');

        // Apply the filter for quantity if it's set
        if ($quantityFilter !== '') {
            $query->where('quantity', '=', $quantityFilter);
        }

        // Paginate the results
        $books = $query->paginate(10);

        return view('admin.books.index', compact('books', 'quantityFilter'));
    }

    public function create()
    {
        $authors = Author::all(); 
        $books = Book::all();
        $genres = Genre::all();
        $book = new \App\Models\Book(); // ✅ Add this line

        return view('admin.books.create', compact('authors',  'books', 'genres', 'book'));
    }



    public function store(Request $request)
    {
        // Step 1: Validate incoming data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'photo_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'photo_3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'photo_4' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string',
            'quantity' => 'integer|min:1',
            'full' => 'nullable|string',
            'author_id' => 'required|exists:authors,id',
            'genre_id' => 'nullable|array',
            'genre_id.*' => 'exists:genres,id',
            'status' => 'nullable|string',
            'pages' => 'nullable|string|max:255',
            'publishing_date' => 'nullable|string',
            'cover' => 'nullable|string|max:255',
            'manual_created_at' => 'nullable|date_format:Y-m-d\TH:i',
        ]);

        // Step 2: Handle image uploads
        foreach (['photo', 'photo_2', 'photo_3', 'photo_4'] as $key) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $uniqueFileName = time() . '_' . uniqid() . '.webp';
                $image = Image::make($file)
                    ->resize(800, 600, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->encode('webp', 65);

                $imagePath = 'uploads/books/' . $uniqueFileName;
                Storage::disk('public')->put($imagePath, $image);
                $validatedData[$key] = $imagePath;
            }
        }

        // Step 3: Format dates and uploader
        if ($request->has('manual_created_at')) {
            $validatedData['manual_created_at'] = Carbon::parse($request->input('manual_created_at'));
        }
        $validatedData['uploader_id'] = auth()->id();

        // Step 4: Create book (excluding genres)
        $book = Book::create(collect($validatedData)->except('genre_id')->toArray());  // ✅ exclude genre_id

        // Step 5: Sync genres into pivot
        if ($request->filled('genre_id')) {
            $book->genres()->sync($request->input('genre_id'));
        }

        // Step 6: Notify + cache
        $this->notifySubscribers($book);
        $this->rebuildHomePageCache();

        return redirect()->route('admin.books.index')->with('success', 'წიგნი დამატებულია წარმატებით.');
    }



    protected function rebuildHomePageCache()
    {
        // Clear and rebuild `home_books` cache
        Cache::forget('home_books');
        Cache::remember('home_books', 600, function () {
            return Book::orderBy('manual_created_at', 'DESC')
                ->where('hide', '0')
                ->take(8)
                ->get();
        });

        // Clear and rebuild `home_news` cache
        Cache::forget('home_news');
        Cache::remember('home_news', 600, function () {
            return BookNews::where('title', '!=', 'წესები და პირობები')
                ->where('title', '!=', 'ბუკინისტებისათვის')
                ->latest()
                ->take(6)
                ->get();
        });

        // Clear and rebuild `bukinistebisatvis` cache
        Cache::forget('home_bukinistebisatvis');
        Cache::remember('home_bukinistebisatvis', 600, function () {
            return BookNews::where('title', '!=', 'ბუკინისტებისათვის')
                ->latest()
                ->take(6)
                ->get();
        });

        // Clear and rebuild `popular_books` cache
        Cache::forget('popular_books');
        Cache::remember('popular_books', 3600, function () {
            return Book::orderBy('views', 'desc')->take(10)->get();
        });

        // Clear and rebuild `top_books` cache
        Cache::forget('top_books');
        Cache::remember('top_books', 3600, function () {
            return Book::with('author')
                ->join('order_items', 'order_items.book_id', '=', 'books.id')
                ->select('books.id', 'books.title', 'books.author_id', 'books.photo')
                ->selectRaw('SUM(order_items.quantity) as total_sold')
                ->groupBy('order_items.book_id', 'books.id', 'books.title', 'books.author_id', 'books.photo')
                ->orderByDesc('total_sold')
                ->take(10)
                ->get();
        });
    }




    protected function notifySubscribers($book)
    {
        // Retrieve all subscribers
        $subscribers = Subscriber::all();

        // Prepare the email content
        $messageContent = "ბუკინისტებზე დაემატა ახალი წიგნი '{$book->title}'.";

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new SubscriptionNotification($messageContent));
        }
    }






    public function update(Request $request, Book $book)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'manual_created_at' => 'nullable|date_format:Y-m-d\TH:i',
            'description' => 'required|string',
            'quantity' => 'integer|min:0',
            'full' => 'nullable|string',
            'author_id' => 'required|exists:authors,id',
            'genre_id' => 'nullable|array',
            'genre_id.*' => 'exists:genres,id',
            'status' => 'nullable|string',
            'pages' => 'nullable|string|max:255',
            'publishing_date' => 'nullable|string',
            'cover' => 'nullable|string|max:255',
        ]);

        if ($request->has('manual_created_at')) {
            $book->manual_created_at = Carbon::parse($request->input('manual_created_at'));
        }

        foreach (['photo', 'photo_2', 'photo_3', 'photo_4'] as $key) {
            if ($request->hasFile($key)) {
                if ($book->{$key} && Storage::disk('public')->exists($book->{$key})) {
                    Storage::disk('public')->delete($book->{$key});
                }

                $uniqueFileName = time() . '_' . uniqid() . '.webp';
                $image = Image::make($request->file($key))
                    ->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->encode('webp', 75);

                $imagePath = 'uploads/books/' . $uniqueFileName;
                Storage::disk('public')->put($imagePath, $image);
                $validatedData[$key] = $imagePath;
            }
        }

        $book->update(collect($validatedData)->except('genre_id')->toArray());

        // Update genres
        if ($request->filled('genre_id')) {
            $book->genres()->sync($request->input('genre_id'));
        } else {
            $book->genres()->detach(); // No genres selected
        }

        $this->rebuildHomePageCache();

        return redirect()->route('admin.books.index')->with('success', 'წიგნი განახლდა წარმატებით.');
    }












    public function edit(Book $book)
    {
        $authors = Author::all();
        $categories = Category::all();
        $genres = Genre::all();
        return view('admin.books.edit', compact('book', 'authors', 'categories', 'genres'));
    }



    public function destroy(Book $book)
    {
        if ($book->photo) {
            Storage::delete('public/' . $book->photo);
        }

        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'წიგნი წარმატებით წაიშალა.');
    }




    public function toggleVisibility($id)
    {
        $book = Book::findOrFail($id);
        $book->hide = !$book->hide; // Toggle the hide status
        $book->save();

        return redirect()->back()->with('success', 'მასალა წარმატებით განახლდა.');
    }

    // app/Http/Controllers/AdminController.php


    public function usersList()
    {

        $users = User::where('role', '!=', 'publisher')
            ->where('role', '!=', 'admin')
            ->paginate(10);

        return view('admin.users_list', compact('users'));
    }

    public function usersTransactions()
    {
        // Fetch users and their total items, including the latest order's amount
        $users = User::withCount(['orders as total_items' => function ($query) {
            $query->join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->selectRaw('sum(order_items.quantity)'); // Sum the quantities from order_items
        }])
            ->select('users.*')
            ->addSelect([
                'last_order_total' => Order::select('total')
                    ->whereColumn('orders.user_id', 'users.id')
                    ->orderBy('created_at', 'desc') // Get the total of the latest order regardless of status
                    ->limit(1), // Get the total of the latest order
                'last_order_date' => Order::select('created_at')
                    ->whereColumn('orders.user_id', 'users.id')
                    ->orderBy('created_at', 'desc')
                    ->limit(1), // Get the date of the last order
            ])
            ->orderByDesc('last_order_date') // Sort users based on the last order date
            ->paginate(10); // Paginate users

        return view('admin.users_transactions', compact('users'));
    }



    public function markAsDelivered($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->status = 'delivered'; // You can change 'delivered' to any text you want
        $order->save();

        return redirect()->back()->with('success', 'შეკვეთა წარმატებით მონიშნულია როგორც მიწოდებული!');
    }

    public function undoDelivered($orderId)
    {
        $order = Order::findOrFail($orderId);

        // You can choose what status you want to restore
        $order->status = 'pending'; // or maybe 'processing', or whatever your logic is
        $order->save();

        return redirect()->back()->with('success', 'შეკვეთის სტატუსი შეცვლილია!');
    }



    public function showUserDetails($id)
    {
        // Fetch user by ID and eager load their orders sorted by created_at
        $user = User::with(['orders' => function ($query) {
            $query->orderBy('created_at', 'desc'); // Order by latest first
        }, 'orders.orderItems'])->findOrFail($id); // Eager load orders and order items

        // Initialize variables
        $newPurchaseTotal = 0;
        $oldTotal = 0;

        // Check if the user has orders
        if ($user->orders->isNotEmpty()) {
            $newPurchaseTotal = $user->orders->first()->total; // New purchase is from the latest order
            $oldTotal = $user->orders->sum('total') - $newPurchaseTotal; // Calculate old total
        }

        return view('admin.user_details', compact('user', 'newPurchaseTotal', 'oldTotal'));
    }



    public function adminsearch(Request $request)
    {
        // Get the search term
        $searchTerm = $request->get('title', '');

        // Log the search keyword in the database if it's present


        // Start a query for books that are not hidden
        $query = Book::where('title', '!=', '');

        // Apply search term filter (combine search fields inside a subquery)
        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('full', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('author', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        // Apply price filter if provided
        if ($request->filled('price_from')) {
            $query->where('price', '>=', $request->input('price_from'));
        }

        if ($request->filled('price_to')) {
            $query->where('price', '<=', $request->input('price_to'));
        }

        // Apply publishing year filter if provided
        if ($request->filled('publishing_date')) {
            $query->where('publishing_date', '=', $request->input('publishing_date'));
        }

        // Apply category filter if provided
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Fetch the results
        $books = $query->orderBy('id', 'DESC')
            ->paginate(10)
            ->appends($request->query());  // Keep the filters in the pagination links

        // Get the search count
        $search_count = $books->total();

        // Fetch authors and categories for any additional use (if needed)
        $authors = Author::all();
        $categories = Category::all();

        // Get the cart items for the logged-in user


        // Return the view with all required data
        return view('admin.search', compact('books', 'authors', 'searchTerm',  'search_count', 'categories'));
    }
}
