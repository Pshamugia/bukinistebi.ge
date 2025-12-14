<?php

use App\Models\EmailLog;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\Exports\UserTransactionsExport;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;

use App\Http\Controllers\OrderController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\BookNewsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\BundleFrontController;
use App\Http\Controllers\TbcCheckoutController;
use App\Http\Controllers\Admin\BundleController;
use App\Http\Controllers\AuctionFrontController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Admin\AuctionController;
use App\Http\Controllers\CookieConsentController;
use App\Http\Controllers\Admin\SubAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\AdminPublisherController;
use App\Http\Controllers\Publisher\PublisherBookController;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Publisher\PublisherAuthorController;
use App\Http\Controllers\Publisher\PublisherAccountController;
use App\Http\Controllers\Admin\BookController as AdminBookController;


use App\Http\Controllers\Admin\AuthorController as AdminAuthorController;
use App\Http\Controllers\AuthorController;  // This is for front-end authors
use App\Http\Controllers\Admin\BookNewsController as AdminBookNewsController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;






//FOR COOKIES
Route::post('/store-cookie-consent', [CookieConsentController::class, 'storeUserBehavior'])->name('store-user-behavior');


Route::get('/guest-order-success/{orderId}', [OrderController::class, 'guestOrderSuccess'])
    ->name('guest.order.success');


Route::get('/clear-all-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return '✅ All caches cleared';
});



Route::get('/bundles', [BundleFrontController::class, 'index'])->name('bundles.index.public');
Route::get('/bundles/{slug}', [BundleFrontController::class, 'show'])->name('bundles.show');



// Home Route - Display all books
Route::get('/', [BookController::class, 'welcome'])->name('welcome');
Route::get('/book-news', [BookNewsController::class, 'index'])->name('book_news.index');
Route::get('/book-news/{id}', [BookNewsController::class, 'show'])->name('book_news.show');
Route::get('/all_book_news', [BookNewsController::class, 'allbooksnews'])->name('allbooksnews');
Route::get('/full_author/{name}/{id}', [AuthorController::class, 'full_author'])->name('full_author');
Route::get('/terms_conditions', [BookNewsController::class, 'terms'])->name('terms_conditions');
Route::post('/admin/send-subscriber-email', [SubscriptionController::class, 'sendEmailToSubscribers'])->name('send.subscriber.email');


Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscribe');
Route::get('unsubscribe/{email}', [SubscriptionController::class, 'unsubscribe'])->name('unsubscribe');
Route::get('/track-open/{email}', function ($email) {
    try {
        EmailLog::updateOrCreate(
            ['email' => decrypt($email)],
            ['opened_at' => now()]
        );
    } catch (\Throwable $e) {
        Log::error('Open tracking failed: ' . $e->getMessage());
    }

    // Return a 1x1 transparent GIF
    return response(base64_decode('R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=='))
        ->header('Content-Type', 'image/gif');
})->name('track.email.open');



Route::post('/rate-article/{bookId}', [BookController::class, 'rateArticle'])->name('article.rate');


// 
Route::get('lang/{locale}', [App\Http\Controllers\BookController::class, 'setLocale'])->name('setLocale');

Route::get('/lang-test', function () {
    return 'Current language is: ' . app()->getLocale();
});

// auction FRONT
Route::get('/auction/{auction}', [AuctionFrontController::class, 'show'])->name('auction.show');
Route::post('/auction/{auction}/bid', [AuctionFrontController::class, 'bid'])
    ->middleware(['auth', 'profile.complete'])->name('auction.bid');
Route::get('/auctions', [AuctionFrontController::class, 'index'])->name('auction.index');
Route::get('/my-bids', [AuctionFrontController::class, 'myAuctionDashboard'])->middleware('auth')->name('my.bids');
Route::get('/auction/{auction}/bids', [AuctionFrontController::class, 'getBids'])->middleware('auth')->name('auction.bids');
Route::get('/auctions/rules', [AuctionFrontController::class, 'rules'])->name('auction.rules');

Route::get('/check-user-profile', function () {
    $user = auth()->user();

    return response()->json([
        'missing_fields' => empty($user->phone) || empty($user->address)
    ]);
})->middleware('auth');


// Admin status route
Route::get('/admin-status', function () {
    $user = Auth::user();

    // Ensure the user is authenticated and has an admin role
    $isAdminOnline = Auth::check() && $user && $user->role === 'admin';

    return response()->json(['online' => $isAdminOnline]);
});



Route::middleware('auth')->group(function () {
    Route::get('/admin-login-status', function () {
        $user = Auth::user();
        if ($user && $user->role === 'admin') {
            Cache::put('admin_online', true, now()->addMinutes(5)); // Admin is online
        }
        return response()->json(['status' => 'checked']);
    });

    // Clear cache when admin logs out
    Route::get('/logout', function () {
        $user = Auth::user();
        if ($user && $user->role === 'admin') {
            Cache::forget('admin_online');
        }
        return redirect('/');
    });
});

// Authentication Routes (Handled by Breeze)
require __DIR__ . '/auth.php';

// Authors Management (Front-End)
Route::resource('authors', AuthorController::class);

// Categories Management (Front-End)
Route::resource('categories', CategoryController::class);

// Books Management (Front-End)
Route::resource('books', BookController::class);
Route::get('/books/{title}/{id}', [BookController::class, 'full'])->name('full');
Route::get('/full_news/{title}/{id}', [BookNewsController::class, 'full_news'])->name('full_news');

// Search Route (as per the search form in the layout)
Route::get('/search', [BookController::class, 'search'])->name('search');
Route::get('/search/suggest', [BookController::class, 'suggest'])
    ->name('search.suggest');
Route::get('/books', [BookController::class, 'books'])->name('books');


// souvenirs
Route::get('/souvenirs', [BookController::class, 'souvenirs'])->name('souvenirs.index');
Route::get('/souvenirs/{title}/{id}', [BookController::class, 'full_souvenir'])->name('full_souvenir');



// send us book order
Route::get('/order_us', [BookController::class, 'order_us'])->name('order_us');
Route::post('/order_request_book', [BookController::class, 'sendRequest'])->name('order.request.book'); // Handle form submission

// podcast
Route::get('/podcast', [BookController::class, 'podcast'])->name('podcast');

//genre
Route::get('/genres/{id}-{slug}', [BookController::class, 'byGenre'])->name('genre.books');

Route::post('/direct-book-pay', [TbcCheckoutController::class, 'directPayFromBook'])->name('book.direct.pay');
// routes/web.php
Route::post('/bundle/{bundle}/direct-pay', [TbcCheckoutController::class, 'directPayBundle'])
    ->name('bundle.direct.pay');

// TBC E-Commerce Routes within auth middleware
Route::middleware('auth')->group(function () {
    // Initialize payment and show checkout page
    Route::post('/tbc-checkout', [TbcCheckoutController::class, 'initializePayment'])->name('tbc-checkout');
    Route::get('/tbc-checkout/{orderId}', [TbcCheckoutController::class, 'show'])->name('tbc.checkout');



    Route::post('/auction-payment', [TbcCheckoutController::class, 'initializeAuctionPayment'])
        ->withoutMiddleware([\App\Http\Middleware\SetLocale::class])
        ->name('auction.payment');

    // Process TBC payment
    Route::post('/process-payment', [TbcCheckoutController::class, 'processPayment'])->name('process.payment');


    Route::get('/order-failed', function () {
        return view('order.failed'); // Create a view named 'order.failed' or adjust to an appropriate view
    })->name('order.failed');

    Route::get('/order/success', function () {
        return view('order.success');
    })->name('order.success');


    Route::get('/order/guest-success/{order}', [TbcCheckoutController::class, 'guestSuccess'])
        ->name('order.guest-success');


    Route::get("/order/{id}/status/",  [OrderController::class, 'status'])->name('order.status');
});

// Handle the TBC payment callback
Route::get('/tbc-callback', [TbcCheckoutController::class, 'handleCallback'])->name('tbc.callback');
Route::get('/tbc-return', [TbcCheckoutController::class, 'tbcReturnUrl'])->name('tbc.returnurl');


Route::get('/guest-order-success/{orderId}', [OrderController::class, 'guestOrderSuccess'])
    ->name('guest.order.success');
// Cart Routes (Add 'auth' middleware to ensure only logged-in users can access the cart)

Route::middleware('auth')->group(function () {

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{book}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove/{book}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/remove-bundle/{bundle}', [CartController::class, 'removeBundle'])
        ->name('cart.removeBundle');

    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/toggle', [CartController::class, 'toggle'])->name('cart.toggle');
    Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::post('/cart/add-bundle/{bundle}', [CartController::class, 'addBundle'])->name('cart.addBundle');

    Route::get('/cart/count', function () {
        $items = session('cart.items', []); // adjust this path as needed
        $count = is_array($items) ? count($items) : 0;

        return response()->json([
            'count' => $count
        ]);
    });



    // Orders Routes
    Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

    // Account Routes for editing user profile
    Route::get('/account/edit', [AccountController::class, 'edit'])->name('account.edit');
    Route::post('/account/update', [AccountController::class, 'update'])->name('account.update');
    // order Checkout
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');

    Route::get('/purchase-history', [OrderController::class, 'purchaseHistory'])->name('purchase.history')->middleware('auth');
});
Route::get('/order-courier/{orderId}', [OrderController::class, 'orderCourier'])->name('order_courier');



// Publisher routes with 'publisher' role middleware
Route::middleware(['auth', 'role:publisher'])->group(function () {

    // Publisher dashboard
    Route::get('/publisher/dashboard', function () {
        return view('publisher.dashboard');
    })->name('publisher.dashboard');

    Route::get('/publisher/account/edit', [PublisherAccountController::class, 'edit'])->name('publisher.account.edit');
    Route::match(['put', 'post'], '/publisher/account/update', [PublisherAccountController::class, 'update'])->name('publisher.account.update');
    Route::get('/publisher/my-books', [PublisherBookController::class, 'myBooks'])->name('publisher.my_books');


    // Routes for Publisher's Book Upload
    Route::resource('publisher/books', PublisherBookController::class)->only(['create', 'store'])->names([
        'create' => 'publisher.books.create',
        'store' => 'publisher.books.store',
    ]);

    // Routes for Publisher's Author Management
    Route::get('/publisher/authors/create', [PublisherAuthorController::class, 'create'])->name('publisher.authors.create');
    Route::post('/publisher/authors/store', [PublisherAuthorController::class, 'store'])->name('publisher.authors.store');
});

// Publisher registration and login routes
Route::get('/register/publisher', [RegisteredUserController::class, 'createPublisherForm'])->name('register.publisher.form');
Route::post('/register/publisher', [RegisteredUserController::class, 'storePublisher'])->name('register.publisher');
Route::get('/login/publisher', [AuthenticatedSessionController::class, 'createPublisherLoginForm'])->name('login.publisher.form');
Route::post('/login/publisher', [AuthenticatedSessionController::class, 'storePublisherLogin'])->name('login.publisher');

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);



// Publisher dashboard and book upload routes (restricted to publisher role)
Route::middleware(['auth', 'role:publisher'])->group(function () {
    Route::get('/publisher/dashboard', function () {
        return view('publisher.dashboard');
    })->name('publisher.dashboard');

    Route::resource('publisher/books', PublisherBookController::class)->only(['create', 'store'])->names([
        'create' => 'publisher.books.create', // Route for publisher book creation
        'store' => 'publisher.books.store',
    ]);
});

// Admin routes with admin middleware and prefix
Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {



    Route::get('/email-stats', [SubscriptionController::class, 'emailStats'])->name('admin.email.stats');

    Route::post('/email-retry', function () {
        \Illuminate\Support\Facades\Artisan::call('queue:retry all');
        return redirect()->back()->with('success', 'All failed emails have been retried.');
    })->name('admin.email.retry');
    // Admin dashboard route
    Route::get('/', [DashboardController::class, 'index'])->name('admin');

    Route::get('/admin/subscribe-all-users', [SubscriptionController::class, 'subscribeAllUsers'])
        ->name('admin.subscribeAllUsers');
    // Publishers Activity Route
    Route::get('/publishers/activity', [AdminPublisherController::class, 'activity'])->name('admin.publishers.activity');




    // Authors CRUD routes (Admin)
    Route::resource('authors', AdminAuthorController::class, ['as' => 'admin']);


    // Auctions Management (Admin)
    Route::get('/auctions', [AuctionController::class, 'index'])->name('admin.auctions.index');
    Route::get('/auctions/create', [AuctionController::class, 'create'])->name('admin.auctions.create');
    Route::post('/auctions', [AuctionController::class, 'store'])->name('admin.auctions.store');
    Route::get('/admin/auction-participants', [AuctionController::class, 'participants'])->name('admin.auction.participants');


    Route::resource('bundles', BundleController::class, ['as' => 'admin']);


    // Auction Update/Edit Routes
    Route::get('/auctions/{auction}/edit', [AuctionController::class, 'edit'])->name('admin.auctions.edit');
    Route::delete('/admin/auctions/{auction}', [AuctionController::class, 'destroy'])
        ->name('admin.auctions.destroy');

    Route::put('/auctions/{auction}', [AuctionController::class, 'update'])->name('admin.auctions.update');
    Route::get('/auction/{id}/bids', [AuctionController::class, 'bidsPartial'])->name('auction.bids');
    Route::get('/dashboard/auctions', [AuctionController::class, 'userDashboard'])->middleware('auth')->name('auction.dashboard');
    Route::post('/pay-auction-fee', [TbcCheckoutController::class, 'payAuctionFee'])->name('auction.fee.payment')->middleware('auth');


    // Books CRUD routes (Admin)
    Route::resource('books', AdminBookController::class, ['as' => 'admin']);

    // Categories CRUD routes (Admin)
    Route::resource('categories', AdminCategoryController::class, ['as' => 'admin']);

    Route::resource('genres', GenreController::class, ['as' => 'admin']);


    Route::get('/admin/guest-orders/{order}', [AdminBookController::class, 'guestOrderDetails'])
        ->name('admin.guest.order.details');



        // subadmins
Route::get('/sub-admins', [SubAdminController::class, 'index'])
    ->name('admin.subadmins');

Route::post('/sub-admins/{user}', [SubAdminController::class, 'update'])
    ->name('admin.subadmins.update');

Route::post('/sub-admins', [SubAdminController::class, 'create'])
    ->name('admin.subadmins.create');




    // Book News CRUD routes (Admin)
    Route::resource('book-news', AdminBookNewsController::class, ['as' => 'admin']);
    Route::post('/books/{id}/toggleVisibility', [AdminBookController::class, 'toggleVisibility'])->name('admin.books.toggleVisibility');
    Route::get('/user-keywords', [AdminPublisherController::class, 'showUserKeywords'])->name('admin.user.keywords');
    Route::get('/subscribers', [SubscriptionController::class, 'subscribers'])->name('admin.subscribers');
    Route::delete('/admin/subscribers/{id}', [SubscriptionController::class, 'destroy'])->name('admin.subscribers.destroy');

    Route::get('/top-rated-articles', [DashboardController::class, 'topRatedArticles'])->name('admin.topRatedArticles');

    // Show User Preferences with Purchases
    Route::get('/user-preferences-with-purchases', [CookieConsentController::class, 'showUserPreferencesWithPurchases'])
        ->name('admin.user.preferences.purchases');
    Route::get('/user-preferences/{identifier}', [CookieConsentController::class, 'showUserJourney'])
        ->name('admin.user.preferences.journey'); // ✅ now route name exists
    Route::get('/user-preferences-paths', [CookieConsentController::class, 'userPathChartData'])
        ->name('admin.user.preferences.chartdata');


    // FOR PUBLISHERS TO ALLOW HIDE/SHOW
    Route::post('/books/{id}/toggle-visibility', [AdminPublisherController::class, 'toggleVisibility'])->name('books.toggleVisibility');

    //users transacions
    Route::get('/admin/users-transactions', [AdminBookController::class, 'usersTransactions'])->name('admin.users_transactions')->middleware('auth', 'admin'); // Ensure only admin can access
    Route::get('/admin/users/{id}', [AdminBookController::class, 'showUserDetails'])->name('admin.user.details')->middleware('auth', 'admin');
    Route::get('/admin/users/transactions/export', [AdminBookController::class, 'exportUserTransactions'])
        ->name('admin.users.transactions.export');

    Route::get('/publishers/export', [AdminPublisherController::class, 'exportAllPublishers'])
        ->name('admin.publishers.export');

    // 2) Export a SINGLE publisher’s sales (date range optional)
    Route::get('/publishers/{publisher}/export', [AdminPublisherController::class, 'exportPublisher'])
        ->name('admin.publisher.export');

    // 3) Export a SINGLE publisher as "Sold book title – price" (date range optional)
    Route::get('/publishers/{publisher}/export-titles', [AdminPublisherController::class, 'exportPublisherTitles'])
        ->name('admin.publisher.export.titles');


    Route::get('/admin/users', [AdminBookController::class, 'usersList'])->name('admin.users.list')->middleware('auth', 'admin');

    Route::get('/search', [AdminBookController::class, 'adminsearch'])->name('admin.search')->middleware('auth', 'admin'); // Ensure only admin can access
    Route::put('/admin/orders/{order}/mark-delivered', [App\Http\Controllers\Admin\BookController::class, 'markAsDelivered'])->name('admin.markAsDelivered');
    Route::put('/admin/orders/{order}/undo-delivered', [App\Http\Controllers\Admin\BookController::class, 'undoDelivered'])->name('admin.undoDelivered');
    Route::get('/admin/book-orders', [BookController::class, 'adminBookOrders'])->name('admin.book_orders');
Route::post('/book-orders/{order}/done', [\App\Http\Controllers\Admin\BookController::class, 'markDone'])
    ->name('admin.book_orders.done');
    Route::get('/book-orders', [AdminBookController::class, 'searchOrders'])
        ->name('admin.book_orders.index');
        Route::get('/admin/orders/{order}/label', [AdminBookController::class, 'print'])
    ->name('admin.order.label');





    Route::post('authors/quick-store', [AdminAuthorController::class, 'quickStore'])
        ->name('admin.authors.quick-store');
});




Route::post('/cart/toggle-bundle', [\App\Http\Controllers\CartController::class, 'toggleBundle'])
    ->name('cart.toggleBundle');
