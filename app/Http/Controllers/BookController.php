<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Genre;
use App\Models\Author;
use App\Models\Auction;
use App\Models\BookNews;
use App\Models\Category;
use App\Models\BookOrder;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ArticleRating;
use App\Models\SearchKeyword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class BookController extends Controller
{
    /**
     * Display a listing of the books on the welcome page.
     */
    public function welcome()
    {
        $books = Cache::remember('home_books_' . app()->getLocale(), 600, function () {
            return Book::orderBy('manual_created_at', 'DESC')
                ->where('hide', '0')
                ->where('auction_only', false)
                ->where('language', app()->getLocale()) // ✅ ADD THIS
                ->whereDoesntHave('genres', function ($q) {
                    $q->where('name', 'სუვენირები')->orWhere('name_en', 'Souvenirs');
                })
                ->take(8)
                ->get();
        });


        $cartItemIds = [];
        if (Auth::check()) {
            $cart = Auth::user()->cart;
            if ($cart) {
                $cartItemIds = $cart->cartItems->pluck('book_id')->toArray();
            }
        }

        $locale = app()->getLocale();

        $news = BookNews::query()
            ->when($locale === 'en', fn($q) => $q->whereNotNull('title_en'))
            ->when($locale === 'ka', fn($q) => $q->whereNotNull('title'))
            ->where('title', '!=', 'წესები და პირობები')
            ->where('title', '!=', 'ბუკინისტებისათვის')
            ->latest()
            ->paginate(6);


        $bukinistebisatvis = Cache::remember('home_bukinistebisatvis', 600, function () {
            return BookNews::where('title', '!=', 'ბუკინისტებისათვის')->latest()->paginate(6);
        });

        $popularBooks = Cache::remember('popular_books', 3600, function () {
            return Book::orderBy('views', 'desc')->take(10)->get();
        });

        $topBooks = Cache::remember('top_books', 3600, function () {
            return Book::with('author')
                ->join('order_items', 'order_items.book_id', '=', 'books.id')
                ->where('auction_only', false)
                ->where('hide', '0')
                ->select('books.id', 'books.title', 'books.author_id', 'books.photo')
                ->selectRaw('SUM(order_items.quantity) as total_sold')
                ->groupBy('order_items.book_id', 'books.id', 'books.title', 'books.author_id', 'books.photo')
                ->orderByDesc('total_sold')
                ->take(10)
                ->get();
        });


        $locale = App::getLocale();

        $topRatedArticle = Cache::remember("top_rated_articles_{$locale}", 3600, function () use ($locale) {
            $query = DB::table('article_ratings')
                ->join('books', 'article_ratings.book_id', '=', 'books.id')
                ->select('books.id as book_id', DB::raw('AVG(article_ratings.rating) as avg_rating'))
                ->where('books.quantity', '>', 0)
                ->where('auction_only', false)
                ->where('hide', '0')
                ->groupBy('books.id')
                ->orderByDesc('avg_rating')
                ->limit(10);

            if ($locale === 'en') {
                $query->where('books.language', 'en');
            }

            $topRated = $query->get();
            $bookIds = $topRated->pluck('book_id');

            return \App\Models\Book::with('author')
                ->whereIn('id', $bookIds)
                ->get();
        });

        $georgianAlphabet = [
            'ა',
            'ბ',
            'გ',
            'დ',
            'ე',
            'ვ',
            'ზ',
            'თ',
            'ი',
            'კ',
            'ლ',
            'მ',
            'ნ',
            'ო',
            'პ',
            'ჟ',
            'რ',
            'ს',
            'ტ',
            'უ',
            'ფ',
            'ქ',
            'ღ',
            'ყ',
            'შ',
            'ჩ',
            'ც',
            'ძ',
            'წ',
            'ჭ',
            'ხ',
            'ჯ',
            'ჰ'
        ];

        $genres = \App\Models\Genre::all()->sortBy(function ($genre) use ($georgianAlphabet) {
            // Remove spaces, symbols, punctuation
            $cleaned = preg_replace('/[^ა-ჰ]/u', '', $genre->name);
            $firstLetter = mb_substr($cleaned, 0, 1, 'UTF-8');
            $index = array_search($firstLetter, $georgianAlphabet);
            return $index === false ? 999 : $index;
        })->values();


        $isHomePage = true;


        $activeAuctions = Auction::where('is_active', true)
            ->where('is_approved', true)
            ->where('end_time', '>', now())
            ->with('book.images')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();


        $showAuctionSidebar = $activeAuctions->count() >= 3;

        return view('welcome', compact(
            'books',
            'cartItemIds',
            'news',
            'popularBooks',
            'isHomePage',
            'bukinistebisatvis',
            'topBooks',
            'topRatedArticle',
            'genres',
            'activeAuctions',
            'showAuctionSidebar'
        ));
    }



    public function setLocale($locale)
    {
        Session::put('locale', $locale);
        App::setLocale($locale);

        Log::info('✅ setLocale triggered, locale set to: ' . $locale);

        return redirect()->back();
    }


    public function byGenre($id)
    {
        $genre = Genre::findOrFail($id);

        $query = $genre->books()
            ->where('hide', 0)
            ->where('auction_only', 0)
            ->whereDoesntHave('auction', function ($q) {
                $q->where('is_active', true)
                    ->where('end_time', '>', now());
            })

            ->where('language', app()->getLocale())
            ->whereDoesntHave('genres', function ($q) {
                $q->where('name', 'სუვენირები')->orWhere('name_en', 'Souvenirs');
            })
            ->when(request('exclude_sold'), function ($q) {
                $q->where('quantity', '>', 0);
            })
            ->when(request('condition'), function ($q) {
                if (request('condition') === 'new') {
                    $q->where('condition', 'new');
                } elseif (request('condition') === 'used') {
                    $q->where('condition', 'used');
                }
            });



        // ✅ Sorting (same logic as in books())
        if (request('sort') === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif (request('sort') === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest(); // default
        }

        $books = $query
            ->paginate(12)
            ->appends(request()->query()); // keep params in pagination

        // cart ids
        $cartItemIds = [];
        if (Auth::check()) {
            $cart = Auth::user()->cart;
            if ($cart) {
                $cartItemIds = $cart->cartItems->pluck('book_id')->toArray();
            }
        }

        // 🔥 AJAX RESPONSE (CRITICAL)
        if (request()->ajax()) {
            return view(
                'partials.book-cards',
                compact('books', 'cartItemIds')
            )->render();
        }

        $isHomePage = false;

        return view('books.by_genre', compact('genre', 'books', 'cartItemIds', 'isHomePage'));
    }



    public function order_us()
    {

        return view('order_us');
    }

    public function podcast()
    {

        $books = Book::orderBy('id', 'DESC')->where('hide', '0')->paginate(12);


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

        // Logged in user info
        $user = Auth::user();
        $userEmail = $user ? $user->email : 'no-email@example.com';

        // Save to database
        BookOrder::create([
            'user_id' => $user ? $user->id : null,
            'title' => $request->title,
            'author' => $request->author,
            'publishing_year' => $request->publishing_year,
            'comment' => $request->comment,
            'email' => $userEmail,
        ]);

        // Send Email
        $data = [
            'title' => $request->title,
            'author' => $request->author,
            'publishing_year' => $request->publishing_year,
            'comment' => $request->comment,
            'email' => $userEmail,
        ];

        // Mail::send('order_request_book', $data, function ($message) use ($data) {
        //     $message->to(['pshamugia@gmail.com', 'bukinistebishop@gmail.com'])
        //             ->replyTo($data['email'])
        //             ->subject('წიგნის შეკვეთა მოვიდა');
        // });

        return redirect()->back()->with('success', '<i class="bi bi-check-circle-fill"></i> შენი შეკვეთა მიღებულია. ჩვენ ვიზრუნებთ შენი სურვილის შესრულებაზე!');
    }


    public function adminBookOrders()
    {
        $orders = BookOrder::where('is_done', false)
            ->latest()
            ->paginate(15);

        return view('admin.book_orders', compact('orders'));
    }

    public function markDone(BookOrder $order)
    {
        $order->update(['is_done' => true]);
        return back()->with('success', 'შეკვეთა მონიშნულია დასრულებულად.');
    }





    public function books()
    {
        $query = Book::query()
            ->where('hide', 0)
            ->where('language', app()->getLocale())
            ->where('auction_only', 0)
            ->whereDoesntHave('genres', function ($q) {
                $q->where('name', 'სუვენირები')
                    ->orWhere('name_en', 'Souvenirs');
            })
            ->whereDoesntHave('auction', function ($q) {
                $q->where('is_active', true)
                    ->where('end_time', '>', now());
            })
            ->when(
                request('exclude_sold'),
                fn($q) =>
                $q->where('quantity', '>', 0)
            )
            ->when(
                in_array(request('condition'), ['new', 'used']),
                fn($q) =>
                $q->where('condition', request('condition'))
            );

        // Sorting (stable)
        if (request('sort') === 'price_asc') {
            $query->orderBy('price', 'asc')->orderBy('id', 'desc');
        } elseif (request('sort') === 'price_desc') {
            $query->orderBy('price', 'desc')->orderBy('id', 'desc');
        } else {
            $query->latest('id');
        }

        $books = $query->paginate(12);

        // Cart items
        $cartItemIds = [];
        if (Auth::check() && Auth::user()->cart) {
            $cartItemIds = Auth::user()
                ->cart
                ->cartItems
                ->pluck('book_id')
                ->toArray();
        }

        // AJAX response
        if (request()->ajax()) {
            return view('partials.book-cards', compact('books', 'cartItemIds'))->render();
        }

        // Full page
        return view('books', [
            'books' => $books,
            'cartItemIds' => $cartItemIds,
            'news' => BookNews::latest()->paginate(6),
            'popularBooks' => Book::orderBy('views', 'desc')->take(10)->get(),
            'isHomePage' => false,
        ]);
    }





    public function getRecommendedBooks($userId)
    {
        // Get book IDs from orders
        $orderedBookIds = \App\Models\Order::where('user_id', $userId)
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->pluck('order_items.book_id')
            ->unique()
            ->toArray();

        // Get book IDs from cart
        $cartBookIds = \App\Models\Cart::where('user_id', $userId)
            ->join('cart_items', 'carts.id', '=', 'cart_items.cart_id')
            ->pluck('cart_items.book_id')
            ->unique()
            ->toArray();

        // Combine them
        $userBookIds = array_unique(array_merge($orderedBookIds, $cartBookIds));

        // Get genres from these books
        $genreIds = \App\Models\Book::whereIn('id', $userBookIds)
            ->pluck('genre_id')
            ->unique()
            ->toArray();

        // Recommend other books from these genres
        $recommendedBooks = \App\Models\Book::whereIn('genre_id', $genreIds)
            ->whereNotIn('id', $userBookIds)
            ->where('hide', 0)
            ->where('auction_only', false)
            ->where('language', app()->getLocale())
            ->inRandomOrder()
            ->take(8)
            ->get();

        return $recommendedBooks;
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
            //->where('auction_only', false)
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


        $genreIds = $book->genres->pluck('id');

        $relatedBooks = Book::where('id', '!=', $book->id)
            ->where('hide', 0)
            ->where('quantity', '>', 0) // ✅ Exclude sold-out books
            ->where('auction_only', false)
            ->where('language', app()->getLocale()) // ✅ match current language
            ->whereHas('genres', function ($query) use ($genreIds) {
                $query->whereIn('genres.id', $genreIds);
            })
            ->inRandomOrder()
            ->take(4)
            ->get();


        // Pass the book, ratings, average rating, and rating count to the view
        return view('full', compact('book', 'full_author', 'cartItemIds', 'isHomePage', 'averageRating', 'ratingCount', 'relatedBooks'));
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




    private function detectLanguage($text)
    {
        // Georgian Unicode range: 10A0–10FF and 2D00–2D2F
        if (preg_match('/[\x{10A0}-\x{10FF}\x{2D00}-\x{2D2F}]/u', $text)) {
            return 'ka';
        }

        // Default: English/Latin
        return 'en';
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
        $query = Book::where('hide', 0)
            ->where('auction_only', 0)
            ->whereDoesntHave('auction', function ($q) {
                $q->where('is_active', true)
                    ->where('end_time', '>', now());
            });






        // Apply search term filter (combine search fields inside a subquery)
        if ($searchTerm) {

            $searchTerm = $request->get('title', '');
            $qLower = mb_strtolower($searchTerm);
            $currentLocale = app()->getLocale();

            $query->where(column: function ($q) use ($qLower) {

                // 1) TITLE match (ka + en)
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$qLower}%"]);

                // 2) AUTHOR match multilingual
                $q->orWhereHas('author', function ($a) use ($qLower) {
                    $a->whereRaw('LOWER(name) LIKE ?', ["%{$qLower}%"])
                        ->orWhereRaw('LOWER(name_en) LIKE ?', ["%{$qLower}%"]);
                });

                // 3) DESCRIPTION multilingual
                $q->orWhereRaw('LOWER(description) LIKE ?', ["%{$qLower}%"]);
                $q->orWhereRaw('LOWER(description_en) LIKE ?', ["%{$qLower}%"]);
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

        // ✅ Exclude sold-out books if checkbox is selected
        if ($request->filled('exclude_sold')) {
            $query->where('quantity', '>', 0);
        }

        // Fetch the results
        $books = $query
            ->select('*')
            ->selectRaw("
        CASE 
            WHEN LOWER(title) LIKE ? THEN 1
            WHEN EXISTS (
                SELECT 1 FROM authors 
                WHERE authors.id = books.author_id 
                AND (LOWER(authors.name) LIKE ? OR LOWER(authors.name_en) LIKE ?)
            ) THEN 2
            WHEN LOWER(description) LIKE ? THEN 3
            WHEN LOWER(description_en) LIKE ? THEN 3
            ELSE 4
        END AS priority
    ", [
                "%{$qLower}%",
                "%{$qLower}%",
                "%{$qLower}%",
                "%{$qLower}%",
                "%{$qLower}%"
            ])
            ->orderByRaw("
        CASE 
            WHEN quantity <= 0 THEN 1
            ELSE 0
        END
    ")
            ->orderBy('priority')

            ->orderByRaw("
        CASE 
            WHEN LOWER(language) = ? THEN 0
            ELSE 1
        END
    ", [$currentLocale])

            ->orderBy('title', 'ASC')

            ->paginate(12)
            ->appends(request()->query());




        // Get the search count
        $search_count = $books->total();

        $suggestion = null;

        if ($searchTerm && $search_count === 0) {

            $normalizedSearch = mb_strtolower(trim($searchTerm));

            // Collect candidates
            $candidates = collect();

            // Authors
            Author::select('name', 'name_en')->get()->each(function ($author) use ($candidates) {
                if ($author->name) {
                    $candidates->push($author->name);
                }
                if ($author->name_en) {
                    $candidates->push($author->name_en);
                }
            });

            // Book titles
            Book::select('title')->get()->each(function ($book) use ($candidates) {
                if ($book->title) {
                    $candidates->push($book->title);
                }
            });

            // Similarity buckets
            $matches90 = [];
            $matches80 = [];
            $matches70 = [];

            foreach ($candidates as $candidate) {
                $candidateLower = mb_strtolower($candidate);

                similar_text($normalizedSearch, $candidateLower, $percent);

                if ($percent >= 90) {
                    $matches90[$candidate] = $percent;
                } elseif ($percent >= 80) {
                    $matches80[$candidate] = $percent;
                } elseif ($percent >= 70) {
                    $matches70[$candidate] = $percent;
                }
            }

            // Decide which bucket wins
            if (!empty($matches90)) {
                arsort($matches90);
                $suggestion = array_key_first($matches90);
            } elseif (!empty($matches80)) {
                arsort($matches80);
                $suggestion = array_key_first($matches80);
            } elseif (!empty($matches70)) {
                arsort($matches70);
                $suggestion = array_key_first($matches70);
            }
        }

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

        if ($request->ajax()) {
            return view('partials.search-results', [
                'books' => $books,
                'cartItemIds' => Auth::check() && Auth::user()->cart
                    ? Auth::user()->cart->cartItems->pluck('book_id')->toArray()
                    : []
            ])->render();
        }


        // 👇 KEEP normal page load for non-AJAX
        return view('search', compact(
            'books',
            'authors',
            'searchTerm',
            'cartItemIds',
            'search_count',
            'categories',
            'genres',
            'isHomePage',
            'suggestion'

        ));
    }


    // BookController.php
    // BookController.php
    public function suggest(\Illuminate\Http\Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json([
                'items' => [],
                'didYouMean' => null
            ]);
        }

        $qLower = mb_strtolower($q, 'UTF-8');

        // Detect language of query (KA or EN)
        $lang = $this->detectLanguage($q);

        /* =========================================================
       1) EXISTING BOOK SUGGEST LOGIC (UNCHANGED)
    ========================================================= */

        $books = Book::query()
            ->select(
                'books.id',
                'books.title',
                'books.description',
                'books.description_en',
                'books.author_id',
                'books.photo',
                'books.language',
                'books.quantity'
            )
            ->with(['author:id,name,name_en'])
            ->where('books.hide', 0)
            ->where('books.auction_only', false)
            ->whereDoesntHave('auction', function ($q) {
                $q->where('is_active', true)
                    ->where('end_time', '>', now());
            })
            ->where(function ($qq) use ($qLower) {
                $qq->whereRaw('LOWER(books.title) LIKE ?', ["%{$qLower}%"])
                    ->orWhereRaw('LOWER(books.description) LIKE ?', ["%{$qLower}%"])
                    ->orWhereRaw('LOWER(books.description_en) LIKE ?', ["%{$qLower}%"])
                    ->orWhereHas('author', function ($a) use ($qLower) {
                        $a->whereRaw('LOWER(name) LIKE ?', ["%{$qLower}%"])
                            ->orWhereRaw('LOWER(name_en) LIKE ?', ["%{$qLower}%"]);
                    });
            })
            ->selectRaw("
            CASE
                WHEN LOWER(books.title) LIKE ? THEN 1
                WHEN EXISTS (
                    SELECT 1 FROM authors
                    WHERE authors.id = books.author_id
                      AND (
                          LOWER(authors.name) LIKE ?
                          OR LOWER(authors.name_en) LIKE ?
                      )
                ) THEN 2
                WHEN LOWER(books.description) LIKE ?
                  OR LOWER(books.description_en) LIKE ? THEN 3
                ELSE 4
            END AS match_priority
        ", [
                "%{$qLower}%",
                "%{$qLower}%",
                "%{$qLower}%",
                "%{$qLower}%",
                "%{$qLower}%"
            ])
            ->selectRaw("
            CASE
                WHEN books.quantity <= 0 THEN 1
                ELSE 0
            END AS sold_priority
        ")
            ->orderBy('sold_priority')
            ->orderBy('match_priority')
            ->orderByRaw(
                "CASE WHEN LOWER(books.language) = ? THEN 0 ELSE 1 END",
                [$lang]
            )
            ->orderBy('books.title')
            ->limit(8)
            ->get();

        $items = $books->map(function ($b) {
            $authorName = app()->getLocale() === 'en'
                ? ($b->author->name_en ?: $b->author->name)
                : ($b->author->name ?: $b->author->name_en);

            return [
                'title'  => $b->title,
                'author' => $authorName,
                'url'    => route('full', [
                    'title' => \Illuminate\Support\Str::slug($b->title),
                    'id'    => $b->id
                ]),
                'image'  => $b->photo
                    ? asset('storage/' . $b->photo)
                    : asset('default.webp'),
                'sold'   => $b->quantity <= 0
            ];
        });

        /* =========================================================
       2) DID YOU MEAN (ONLY IF NO ITEMS)
    ========================================================= */

        $didYouMean = null;

        if ($items->isEmpty()) {

            $needle = mb_strtolower($q, 'UTF-8');
            $candidates = collect();

            // Authors
            \App\Models\Author::select('name', 'name_en')->get()->each(function ($a) use ($candidates) {
                if ($a->name)    $candidates->push($a->name);
                if ($a->name_en) $candidates->push($a->name_en);
            });

            // Book titles
            Book::select('title')->get()->each(function ($b) use ($candidates) {
                if ($b->title) $candidates->push($b->title);
            });

            $buckets = [
                90 => [],
                80 => [],
                70 => [],
            ];

            foreach ($candidates as $candidate) {
                similar_text(
                    $needle,
                    mb_strtolower($candidate, 'UTF-8'),
                    $percent
                );

                if ($percent >= 90) {
                    $buckets[90][$candidate] = $percent;
                } elseif ($percent >= 80) {
                    $buckets[80][$candidate] = $percent;
                } elseif ($percent >= 70) {
                    $buckets[70][$candidate] = $percent;
                }
            }

            foreach ([90, 80, 70] as $tier) {
                if (!empty($buckets[$tier])) {
                    arsort($buckets[$tier]);
                    $didYouMean = array_key_first($buckets[$tier]);
                    break;
                }
            }
        }

        /* =========================================================
       3) RESPONSE (BACKWARD-COMPATIBLE)
    ========================================================= */

        return response()->json([
            'items' => $items->values(),
            'didYouMean' => $didYouMean
        ]);
    }






    public function souvenirs()
    {
        $genre = \App\Models\Genre::where('name', 'სუვენირები')
            ->orWhere('name_en', 'Souvenirs')
            ->firstOrFail();

        $books = $genre->books()
            ->where('hide', 0);

        // Optional: exclude sold-out
        if (request('exclude_sold')) {
            $books->where('quantity', '>', 0);
        }

        // Sorting
        if (request('sort') === 'price_asc') {
            $books->orderBy('price', 'asc');
        } elseif (request('sort') === 'price_desc') {
            $books->orderBy('price', 'desc');
        } else {
            $books->latest(); // default
        }

        $books = $books->paginate(9)->appends(request()->query());

        $cartItemIds = auth()->check() && auth()->user()->cart
            ? auth()->user()->cart->cartItems->pluck('book_id')->toArray()
            : [];

        $isHomePage = false;

        return view('souvenirs.index', compact('genre', 'books', 'cartItemIds', 'isHomePage'));
    }



    public function full_souvenir($title, $id)
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


        $genreIds = $book->genres->pluck('id');

        $relatedBooks = Book::where('id', '!=', $book->id)
            ->where('hide', 0)
            ->where('quantity', '>', 0) // ✅ Exclude sold-out books
            ->where('auction_only', false)
            ->where('language', app()->getLocale()) // ✅ match current language
            ->whereHas('genres', function ($query) use ($genreIds) {
                $query->whereIn('genres.id', $genreIds);
            })
            ->inRandomOrder()
            ->take(4)
            ->get();


        // Pass the book, ratings, average rating, and rating count to the view
        return view('souvenirs/full_souvenir', compact('book', 'full_author', 'cartItemIds', 'isHomePage', 'averageRating', 'ratingCount', 'relatedBooks'));
    }
}
