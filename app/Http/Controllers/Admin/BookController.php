<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Author;
use App\Models\BookNews;
use App\Models\Category;
use App\Models\BookOrder;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use App\Mail\SubscriptionNotification;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\UserTransactionsExport;
use App\Models\User; // Include the User model
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Order; // Make sure to include your Order model
use Intervention\Image\Facades\Image; // Add this at the top of your controller



class BookController extends Controller
{

    public function index(Request $request)
    {

        abort_unless(auth()->user()->hasAdminPermission('books.manage'), 403);


$query = Book::with(['author', 'genres', 'publisher'])
    ->where(function ($q) {
        $q->whereNull('auction_only')
          ->orWhere('auction_only', false);
    });

        // ✅ Apply quantity filter
        if ($request->filled('quantity')) {
            if ($request->quantity === '3plus') {
                $query->where('quantity', '>', 3);
            } else {
                $query->where('quantity', $request->quantity);
            }
        }

        // ✅ Apply "show only hidden" filter
        if ($request->filled('hidden') && $request->hidden == '1') {
            $query->where('hide', true);
        }



        // ✅ Sort by most viewed if checkbox is selected
        if ($request->sort === 'views') {
            $query->orderByDesc('views');
        } else {
            $query->latest(); // default sort by created_at
        }

        // ✅ Get paginated books
        $books = $query->paginate(10)->appends($request->all());

        // ✅ For dropdown counts
        $quantityCounts = Book::selectRaw('quantity, COUNT(*) as count')
            ->groupBy('quantity')
            ->pluck('count', 'quantity');

        // ✅ Add 3+ count manually
        $quantityCounts['3plus'] = Book::where('quantity', '>=', 3)->count();

        return view('admin.books.index', compact('books', 'quantityCounts'));
    }




    public function searchOrders(Request $request)
    {
        $query = BookOrder::where(function ($q) {
            $q->where('is_done', false)
                ->orWhereNull('is_done');
        });

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('author', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.book_orders', compact('orders'));
    }



    public function create(Request $request)
    {
        $locale = $request->get('lang', default: app()->getLocale());
        app()->setLocale($locale);

        // ✅ Load all authors with either Georgian or English name
    $authors = Author::where(function ($query) use ($locale) {
    if ($locale === 'en') {
        $query->whereNotNull('name_en');
    } elseif ($locale === 'ru') {
        $query->whereNotNull('name_ru');
    } else {
        $query->whereNotNull('name');
    }
})->get();


        // ✅ Load all genres (same as before)
        if ($locale === 'en') {
            $genres = Genre::whereNotNull('name_en')->get();
        } else {
            $genres = Genre::whereNotNull('name')->get();
        }

            $book = null; // ✅ ADD THIS LINE


        return view('admin.books.create', compact('book', 'authors', 'genres', 'locale'));
    }








    public function store(Request $request)
    {


        abort_unless(auth()->user()->hasAdminPermission('books.manage'), 403);



        // Base validation
        $validated = $request->validate([
            'title'              => 'required|string|max:255',
            'language'           => 'required|in:ka,en,ru',
            'price'              => 'required|numeric',
            'new_price'          => 'nullable|numeric',
            'acquisition_price' => 'nullable|numeric|min:0',
            'thumb_image' => 'nullable|image|mimetypes:image/jpeg,image/png,image/jpg,image/webp|max:5120',
            'photo'              => 'nullable|image|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,image/webp|max:5120',
            'photo_2'            => 'nullable|image|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,image/webp|max:5120',
            'photo_3'            => 'nullable|image|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,image/webp|max:5120',
            'photo_4'            => 'nullable|image|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,image/webp|max:5120',
            'video'              => 'nullable|string',
            'description'        => 'required|string',
            'quantity'           => 'integer|min:0',
            'full'               => 'nullable|string',
            'author_id'          => 'required|exists:authors,id',
            'genre_id'           => 'nullable|array',
            'genre_id.*'         => 'exists:genres,id',
            'status'             => 'nullable|string',
            'pages'              => 'nullable|string|max:255',
            'publishing_date'    => 'nullable|string',
            'cover'              => 'nullable|string|max:255',
            'condition' => 'nullable|in:new,used',
            'manual_created_at'  => 'nullable|date_format:Y-m-d\TH:i',
        ]);

        // Determine if "Souvenirs" is selected
        $souvenirId = \App\Models\Genre::where('name', 'სუვენირები')
            ->orWhere('name_en', 'Souvenirs')
            ->value('id');
        $genreIds = (array) $request->input('genre_id', []);

        // Size validation (only for souvenirs)
        if ($souvenirId && in_array($souvenirId, $genreIds)) {
            $request->validate([
                'size'   => ['required', 'array'],
                'size.*' => ['in:XS,S,M,L,XL,XXL,XXXL'],
            ]);
            $validated['size'] = implode(',', $request->input('size', []));
        } else {
            $validated['size'] = null;
        }


        // ===== THUMB IMAGE (SMALL, SQUARE) =====
        if ($request->hasFile('thumb_image')) {

            $file = $request->file('thumb_image');

            $uniqueFileName = time() . '_' . uniqid() . '_thumb.webp';

            $image = Image::make($file)
                ->fit(400, 400) // square thumbnail
                ->encode('webp', 80);

            $imagePath = 'uploads/books/' . $uniqueFileName;

            Storage::disk('public')->put($imagePath, $image);

            $validated['thumb_image'] = $imagePath;
        }


        // Images -> put paths into $validated
        foreach (['photo', 'photo_2', 'photo_3', 'photo_4'] as $key) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $uniqueFileName = time() . '_' . uniqid() . '.webp';
                $image = \Intervention\Image\Facades\Image::make($file)
                    ->resize(800, null, function ($c) {
                        $c->aspectRatio();
                    })
                    ->encode('webp', 75);
                $imagePath = 'uploads/books/' . $uniqueFileName;
                Storage::disk('public')->put($imagePath, $image);
                $validated[$key] = $imagePath;
            }
        }

        if ($request->filled('manual_created_at')) {
            $validated['manual_created_at'] = \Carbon\Carbon::parse($request->input('manual_created_at'));
        }
        $validated['auction_only'] = $request->has('auction_only');
        $validated['uploader_id']  = auth()->id();

        // Create once (exclude pivot field)
        $book = \App\Models\Book::create(collect($validated)->except('genre_id')->toArray());

        // Sync genres
        if (!empty($genreIds)) {
            $book->genres()->sync($genreIds);
        }

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




    public function update(Request $request, \App\Models\Book $book)
    {
        // Base validation
        $validated = $request->validate([
            'title'              => 'required|string|max:255',
            'language'           => 'required|in:ka,en,ru',
            'price'              => 'required|numeric',
            'new_price'          => 'nullable|numeric',
            'acquisition_price' => 'nullable|numeric|min:0',
            'manual_created_at'  => 'nullable|date_format:Y-m-d\TH:i',
            'description'        => 'required|string',
            'quantity'           => 'integer|min:0',
            'video'              => 'nullable|string',
            'full'               => 'nullable|string',
            'author_id'          => 'required|exists:authors,id',
            'genre_id'           => 'nullable|array',
            'genre_id.*'         => 'exists:genres,id',
            'status'             => 'nullable|string',
            'pages'              => 'nullable|string|max:255',
            'publishing_date'    => 'nullable|string',
            'cover'              => 'nullable|string|max:255',
            'condition' => 'nullable|in:new,used',
            'thumb_image' => 'nullable|image|mimetypes:image/jpeg,image/png,image/jpg,image/webp|max:5120',
            'photo'              => 'nullable|image|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,image/webp|max:5120',
            'photo_2'            => 'nullable|image|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,image/webp|max:5120',
            'photo_3'            => 'nullable|image|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,image/webp|max:5120',
            'photo_4'            => 'nullable|image|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,image/webp|max:5120',
        ]);

        // Determine if "Souvenirs" is selected
        $souvenirId = \App\Models\Genre::where('name', 'სუვენირები')
            ->orWhere('name_en', 'Souvenirs')
            ->value('id');
        $genreIds = (array) $request->input('genre_id', []);

        // Size validation (only for souvenirs)
        if ($souvenirId && in_array($souvenirId, $genreIds)) {
            $request->validate([
                'size'   => ['required', 'array'],
                'size.*' => ['in:XS,S,M,L,XL,XXL,XXXL'],
            ]);
            $validated['size'] = implode(',', $request->input('size', []));
        } else {
            $validated['size'] = null;
        }


        // ===== THUMB IMAGE UPDATE =====
        if ($request->hasFile('thumb_image')) {

            if ($book->thumb_image && Storage::disk('public')->exists($book->thumb_image)) {
                Storage::disk('public')->delete($book->thumb_image);
            }

            $uniqueFileName = time() . '_' . uniqid() . '_thumb.webp';

            $image = Image::make($request->file('thumb_image'))
                ->fit(400, 400)
                ->encode('webp', 80);

            $imagePath = 'uploads/books/' . $uniqueFileName;

            Storage::disk('public')->put($imagePath, $image);

            $validated['thumb_image'] = $imagePath;
        }


        // Images -> put paths into $validated
        foreach (['photo', 'photo_2', 'photo_3', 'photo_4'] as $key) {
            if ($request->hasFile($key)) {
                if ($book->{$key} && Storage::disk('public')->exists($book->{$key})) {
                    Storage::disk('public')->delete($book->{$key});
                }
                $uniqueFileName = time() . '_' . uniqid() . '.webp';
                $image = \Intervention\Image\Facades\Image::make($request->file($key))
                    ->resize(800, null, function ($c) {
                        $c->aspectRatio();
                    })
                    ->encode('webp', 75);
                $imagePath = 'uploads/books/' . $uniqueFileName;
                Storage::disk('public')->put($imagePath, $image);
                $validated[$key] = $imagePath;
            }
        }

        if ($request->filled('manual_created_at')) {
            $validated['manual_created_at'] = \Carbon\Carbon::parse($request->input('manual_created_at'));
        }
        $validated['auction_only'] = $request->has('auction_only');

        // Single update
        $book->update(collect($validated)->except('genre_id')->toArray());

        // Sync genres
        if (!empty($genreIds)) {
            $book->genres()->sync($genreIds);
        } else {
            $book->genres()->detach();
        }

        $this->rebuildHomePageCache();

        return redirect()->route('admin.books.index')->with('success', 'წიგნი განახლდა წარმატებით.');
    }













    public function edit(Book $book)
    {
        $locale = app()->getLocale();

        // Load genres relation so we can detect which ones are already assigned
        $book->load('genres');

        $authors = Author::query();
        if ($locale === 'en') {
            $authors = $authors->whereNotNull('name_en');
        } else {
            $authors = $authors->whereNotNull('name');
        }
        $authors = $authors->get();

        $genres = Genre::query();
        if ($locale === 'en') {
            $genres = $genres->whereNotNull('name_en');
        } else {
            $genres = $genres->whereNotNull('name');
        }
        $genres = $genres->get();

        $categories = Category::all();

        return view('admin.books.edit', compact('book', 'authors', 'categories', 'genres', 'locale'));
    }




    public function destroy(Book $book)
    {

        abort_unless(auth()->user()->hasAdminPermission(permission: 'books.delete'), 403);


        abort_unless(
            auth()->user()->hasAdminPermission('books.delete'),
            403
        );
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


    public function markDone(BookOrder $order)
    {
        $order->update(['is_done' => true]);

        return back()->with('success', 'შეკვეთა მონიშნულია დასრულებულად.');
    }






    // app/Http/Controllers/AdminController.php


    public function usersList()
    {

        $users = User::where('role', '!=', 'publisher')
            ->where('role', '!=', 'admin')
            ->paginate(10);

        $publishers = User::where('role', 'publisher')->with('books')->get();


        return view('admin.users_list', compact('users'));
    }


    public function deleteUser(User $user)
{
    abort_unless(auth()->user()->hasAdminPermission('users.delete'), 403);

    // Safety: prevent deleting admins or publishers
    if (in_array($user->role, ['admin', 'publisher'])) {
        return back()->with('error', 'ამ მომხმარებლის წაშლა დაუშვებელია.');
    }

    // Optional: delete related data safely
    // $user->orders()->delete();

    $user->delete();

    return back()->with('success', 'მომხმარებელი წარმატებით წაიშალა.');
}




    public function usersTransactions(Request $request)
    {
        $filterNotDelivered = $request->delivery_filter === 'not_delivered';
        $search = trim($request->q);

        /* ======================
     | REAL USERS
     ====================== */
        $usersQuery = User::with(['orders' => function ($q) {
            $q->orderBy('created_at', 'desc')
                ->with('orderItems.book');
        }])
            ->whereHas('orders');

        if ($search) {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('orders.orderItems.book', function ($b) use ($search) {
                        $b->where('title', 'like', "%{$search}%");
                    });
            });
        }


        $users = $usersQuery->get();

        if ($filterNotDelivered) {
            $users = $users->filter(function ($u) {
                $last = $u->orders->first();
                return $last && strtolower($last->status) !== 'delivered';
            })->values();
        }

        $realUsers = $users->map(function ($user) {
            $last = $user->orders->first();
            $user->last_order_date  = $last?->created_at;
            $user->last_order_total = $last?->total ?? 0;
            return $user;
        });

        /* ======================
     | GUEST ORDERS
     ====================== */
        $guestQuery = Order::whereNull('user_id')
            ->with('orderItems.book')
            ->orderBy('created_at', 'desc');

        if ($filterNotDelivered) {
            $guestQuery->where('status', '!=', 'delivered');
        }

        if ($search) {
            $guestQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('orderItems.book', function ($b) use ($search) {
                        $b->where('title', 'like', "%{$search}%");
                    });
            });
        }


        $guestOrders = $guestQuery->get()->map(function ($order) {
            return (object)[
                'id'               => null,
                'name'             => $order->name ?? 'Guest',
                'orders'           => collect([$order]),
                'last_order_total' => $order->total ?? 0,
                'last_order_date'  => $order->created_at,
            ];
        });

        /* ======================
     | MERGE + PAGINATE
     ====================== */
        $merged = $realUsers
            ->concat($guestOrders)
            ->sortByDesc('last_order_date')
            ->values();

        $perPage     = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $paged = $merged->slice(
            ($currentPage - 1) * $perPage,
            $perPage
        )->values();

        $allUsers = new LengthAwarePaginator(
            $paged,
            $merged->count(),
            $perPage,
            $currentPage,
            [
                'path'  => request()->url(),
                'query' => request()->query(),
            ]
        );

        return view('admin.users_transactions', [
            'users' => $allUsers
        ]);
    }



 public function saveAdminNote(Request $request)
{
    $request->validate([
        'type' => 'required|in:user,order',
        'id'   => 'required|integer',
        'note' => 'nullable|string',
    ]);

    if ($request->type === 'user') {
        $user = User::findOrFail($request->id);
        $user->admin_note = $request->note;
        $user->save();
    } else {
        // ✅ Guest order
        $order = Order::findOrFail($request->id);
        $order->admin_note = $request->note;
        $order->save();
    }

    return response()->json([
        'ok' => true,
    ]);
}




    public function deleteOrder($orderId)
    {
        $order = Order::findOrFail($orderId);

        $order->delete();

        return back()->with('success', 'შეკვეთა წაიშალა წარმატებით');
    }



    public function guestOrderDetails(Order $order)
    {
        // load order items and books for display
        $order->load('orderItems.book');

        return view('admin.guest_order_details', compact('order'));
    }



    public function markAsDelivered($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->status = 'delivered';   // delivery complete
        $order->save();

        return back()->with('success', 'შეკვეთა დასრულებულია!');
    }

    public function undoDelivered($orderId)
    {
        $order = Order::findOrFail($orderId);

        // Revert to a sensible *pre-delivery* status, not "pending"
        $order->status = $order->payment_method === 'courier'
            ? 'processing'  // courier: still to be delivered/handled
            : 'paid';       // bank transfer/direct pay: already paid

        $order->save();

        return back()->with('success', 'შეკვეთის სტატუსი შეცვლილია!');
    }




    public function showUserDetails($id)
    {
        // Fetch user with orders, order items, books, and publishers
        $user = User::with([
            'orders' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'orders.orderItems.book.publisher'
        ])->findOrFail($id);

        // Calculate totals
        $newPurchaseTotal = 0;
        $oldTotal = 0;

        if ($user->orders->isNotEmpty()) {
            $newPurchaseTotal = $user->orders->first()->total;
            $oldTotal = $user->orders->sum('total') - $newPurchaseTotal;
        }

        // (Optional) You can still load all publishers for another use if needed
        $publishers = User::where('role', 'publisher')->with('books')->paginate(10);

        return view('admin.user_details', compact('user', 'newPurchaseTotal', 'oldTotal', 'publishers'));
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

    public function exportUserTransactions(Request $request)
    {
        $from = $request->filled('from_date') ? Carbon::parse($request->from_date)->startOfDay() : null;
        $to   = $request->filled('to_date')   ? Carbon::parse($request->to_date)->endOfDay()   : null;

        return Excel::download(
            new \App\Exports\UserTransactionsExport($from, $to),
            'user_transactions_' . now()->format('Ymd_His') . '.xlsx'
        );
    }


    //printer

    public function print(Order $order)
    {
        $pdf = Pdf::loadView('admin.labels.order_label', compact('order'))
            ->setPaper([0, 0, 288, 432], 'portrait');
        // 4x6 inch → 288x432 points

        return $pdf->stream("label-{$order->id}.pdf");
    }
}
