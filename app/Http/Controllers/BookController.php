<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Genre;
use App\Models\Author;
use App\Models\BookNews;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ArticleRating;
use App\Models\SearchKeyword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;


class BookController extends Controller
{
    /**
     * Display a listing of the books on the welcome page.
     */
    public function welcome()
{
    $books = Cache::remember('home_books', 600, function () {
        return Book::orderBy('manual_created_at', 'DESC')
            ->where('hide', '0')
            ->take(8)  // Limit to 8 books
            ->get();  // Use get() instead of paginate()
    });

    $cartItemIds = [];
    if (Auth::check()) {
        $cart = Auth::user()->cart;
        if ($cart) {
            $cartItemIds = $cart->cartItems->pluck('book_id')->toArray();
        }
    }

    $news = BookNews::where('title', '!=', 'წესები და პირობები')
            ->where('title', '!=', 'ბუკინისტებისათვის')
            ->latest()
            ->take(6) // Limit the query to 6 results
            ->get(); // Use get() instead of paginate()
    

    $bukinistebisatvis = Cache::remember('home_bukinistebisatvis', 600, function () {
        return BookNews::where('title', '!=', 'ბუკინისტებისათვის')->latest()->paginate(6);
    });

    $popularBooks = Cache::remember('popular_books', 3600, function () {
        return Book::orderBy('views', 'desc')->take(10)->get();
    });

    $topBooks = Cache::remember('top_books', 3600, function () {
        return Book::with('author')
            ->join('order_items', 'order_items.book_id', '=', 'books.id')
            ->select('books.id', 'books.title', 'books.author_id', 'books.photo')
            ->selectRaw('SUM(order_items.quantity) as total_sold')
            ->groupBy('order_items.book_id', 'books.id', 'books.title', 'books.author_id', 'books.photo')
            ->orderByDesc('total_sold')
            ->take(10)
            ->get();
    });


    $topRatedArticle = Cache::remember('top_rated_articles', 3600, function () {
        $topRated = ArticleRating::select('book_id', DB::raw('AVG(rating) as avg_rating'))
            ->groupBy('book_id')
            ->orderByDesc('avg_rating')
            ->limit(10)
            ->get();
    
        $bookIds = $topRated->pluck('book_id')->toArray();
    
        return Book::with('author')
            ->whereIn('id', $bookIds)
            ->get();
    });

    $georgianAlphabet = [
        'ა','ბ','გ','დ','ე','ვ','ზ','თ','ი','კ','ლ','მ','ნ','ო','პ',
        'ჟ','რ','ს','ტ','უ','ფ','ქ','ღ','ყ','შ','ჩ','ც','ძ','წ','ჭ',
        'ხ','ჯ','ჰ'
    ];
    
    $genres = \App\Models\Genre::all()->sortBy(function ($genre) use ($georgianAlphabet) {
        // Remove spaces, symbols, punctuation
        $cleaned = preg_replace('/[^ა-ჰ]/u', '', $genre->name);
        $firstLetter = mb_substr($cleaned, 0, 1, 'UTF-8');
        $index = array_search($firstLetter, $georgianAlphabet);
        return $index === false ? 999 : $index;
    })->values();
    

    $isHomePage = true;

    return view('welcome', compact(
        'books',
        'cartItemIds',
        'news',
        'popularBooks',
        'isHomePage',
        'bukinistebisatvis',
        'topBooks',
        'topRatedArticle',
        'genres'
    ));
}



public function byGenre($id)
{
    $genre = Genre::findOrFail($id);
    $books = $genre->books()->where('hide', 0)->latest()->paginate(12);
    // Get the cart item IDs for the authenticated user, if logged in
    $cartItemIds = [];
    if (Auth::check()) {
        $cart = Auth::user()->cart;
        if ($cart) {
            $cartItemIds = $cart->cartItems->pluck('book_id')->toArray(); // Get all book IDs in the user's cart
        }
    }

     $isHomePage = false;

    return view('books.by_genre', compact('genre', 'books', 'cartItemIds','isHomePage'));
}


    Public function order_us()
    { 

        return view('order_us');
    }

    Public function podcast()
    { 

        $books = Book::orderBy('id','DESC')->where('hide', '0')->paginate(12);


        return view('podcast', compact('books'));
    }


 
    public function sendRequest(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'author' => 'required|string|max:255',
        'publishing_year' => 'nullable|string|max:4',
        'comment' => 'required|string',
 
    ]);

  // Check if user is logged in
  $user = Auth::user();
  $userEmail = $user ? $user->email : 'no-email@example.com'; // Default if not logged in

    $data = [
        'title' => $request->input('title'),
        'author' => $request->input('author'),
        'publishing_year' => $request->input('publishing_year'),
        'comment' => $request->input('comment'),
        'email' => $userEmail,
    ];

      


    // Send email
    Mail::send('order_request_book', $data, function ($message) use ($data) {
        $message->to(['pshamugia@gmail.com', 'bukinistebishop@gmail.com'])
                ->replyTo($data['email']) // Reply to user email if available
                ->subject('წიგნის შეკვეთა მოვიდა');
    });

    return redirect()->back()->with('success', '<i class="bi bi-check-circle-fill"></i> შენი შეკვეთა მიღებულია. ჩვენ ვიზრუნებთ შენი სურვილის შესრულებაზე!');
}



    public function books()
    {
        $books = Book::orderBy('id','DESC')->where('hide', '0')->paginate(12);
    
        // Get the cart item IDs for the authenticated user, if logged in
        $cartItemIds = [];
        if (Auth::check()) {
            $cart = Auth::user()->cart;
            if ($cart) {
                $cartItemIds = $cart->cartItems->pluck('book_id')->toArray(); // Get all book IDs in the user's cart
            }
        }

        $news = BookNews::latest()->paginate(6);
        $popularBooks = Book::orderBy('views', 'desc')->take(10)->get(); // Fetch top 3 most viewed books
        $isHomePage = false;
    
        return view('books', compact('books', 'cartItemIds', 'news', 'popularBooks', 'isHomePage'));
    }


    public function rateArticle(Request $request, $bookId)
    {
        // Validate the rating
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Check if the user has already rated this article
        $existingRating = ArticleRating::where('book_id', $bookId)
                                        ->where('user_id', auth()->id())
                                        ->first();

        if ($existingRating) {
            // Update the existing rating
            $existingRating->rating = $request->rating;
            $existingRating->save();
        } else {
            // Store a new rating
            ArticleRating::create([
                'book_id' => $bookId,
                'user_id' => auth()->id(),
                'rating' => $request->rating,
            ]);
        }

        // Redirect back to the book's full page
        return redirect()->route('full', ['title' => Str::slug(Book::find($bookId)->title), 'id' => $bookId]);
    }



    
    

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        $authors = Author::all();
        $categories = Category::all();
        return view('books.create', compact('authors', 'categories'));
    }



    
    public function full($title, $id)
{
    // Fetch the book by ID, including the author and ensure the book is not hidden
    $book = Book::with('author')->where('hide', '0')->findOrFail($id);
    $book->increment('views'); // Increment views by 1

    // Optionally, ensure the title in the URL matches the book's actual title
    $slug = Str::slug($book->title);

    if ($slug !== $title) {
        // Redirect to the correct URL if the slug in the URL doesn't match the actual book title
        return redirect()->route('full', ['title' => $slug, 'id' => $book->id]);
    }

    // Get all ratings for the book (assuming the ratings are stored in the 'article_ratings' table)
    $ratings = ArticleRating::where('book_id', $id)->get();

    // Calculate the average rating (if any)
    $averageRating = $ratings->avg('rating');

    // Optionally, get the number of ratings (if you want to display the total count)
    $ratingCount = $ratings->count();

    $cartItemIds = [];
    if (Auth::check()) {
        $cart = Auth::user()->cart;
        if ($cart) {
            $cartItemIds = $cart->cartItems->pluck('book_id')->toArray(); // Get all book IDs in the user's cart
        }
    }

    $full_author = Author::first();  // Fetch the first author (you may want to modify this)
    $isHomePage = false;

    // Pass the book, ratings, average rating, and rating count to the view
    return view('full', compact('book', 'full_author', 'cartItemIds', 'isHomePage', 'averageRating', 'ratingCount'));
}





 

    /**
     * Display the specified book's details.
     */
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book)
    {
        $authors = Author::all();
        $categories = Category::all();
        return view('books.edit', compact('book', 'authors', 'categories'));
    }

    /**
     * Update the specified book in storage.
     */ 

    /**
     * Remove the specified book from storage.
     */
    public function destroy(Book $book)
    {
        // Delete photo if exists
        if ($book->photo && file_exists(storage_path('app/public/' . $book->photo))) {
            unlink(storage_path('app/public/' . $book->photo));
        }

        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }

    /**
     * Search for books based on query.
     */
    public function search(Request $request)
{
    // Get the search term
    $searchTerm = $request->get('title', '');

    // Log the search keyword in the database if it's present
    if ($searchTerm) {
        SearchKeyword::create([
            'user_id' => Auth::check() ? Auth::id() : null, // Optional: Store user ID if logged in
            'keyword' => $searchTerm,
        ]);
    }

    // Start a query for books that are not hidden
    $query = Book::where('hide', 0);

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

    // genre filter
    if ($request->filled('genre_id')) {
        $query->whereHas('genres', function ($q) use ($request) {
            $q->where('genres.id', $request->input('genre_id'));
        });
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
    $genres = Genre::all();

    // Get the cart items for the logged-in user
    $cartItemIds = [];
    if (Auth::check()) {
        $cart = Auth::user()->cart;
        if ($cart) {
            $cartItemIds = $cart->cartItems->pluck('book_id')->toArray(); // Get all book IDs in the user's cart
        }
    }

    $isHomePage = false;

    // Return the view with all required data
    return view('search', compact('books', 'authors', 'searchTerm', 'cartItemIds', 'search_count', 'categories', 'genres', 'isHomePage'));
}

    

    
    
    
}
