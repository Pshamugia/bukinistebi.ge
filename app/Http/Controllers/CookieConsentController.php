<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookNews;
use App\Models\Cart;
use App\Models\Order;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CookieConsentController extends Controller
{
    // Display user preferences
   
    



    public function index(Request $request)
{
    $rawVisits = UserPreference::with('user')->latest()->get();

    $grouped = $rawVisits->groupBy(function ($item) {
        return $item->user?->email ?: 'guest-' . $item->guest_id;
    });

    $userPreferences = $grouped->map(function ($entries, $identifier) {
        $latest = $entries->first();

        return (object)[
            'identifier' => urlencode($identifier), // Required for route()
            'label' => Str::startsWith($identifier, 'guest-')
                ? 'Guest: ' . Str::replaceFirst('guest-', '', $identifier)
                : $identifier,
            'cookie_consent' => $latest->cookie_consent,
            'page' => $latest->page,
            'time_spent' => $latest->time_spent,
            'date' => $latest->created_at->format('Y-m-d H:i'),
            'visits' => $entries->count(),
        ];
    })->values();

    $acceptedCount = $rawVisits->where('cookie_consent', 'accepted')->count();
    $rejectedCount = $rawVisits->where('cookie_consent', 'rejected')->count();

    return view('admin.user_preferences_with_purchases', compact(
        'userPreferences',
        'acceptedCount',
        'rejectedCount'
    ));
}
    

    
    
    
    
    
    // Store user behavior (including cookie consent)
    public function storeUserBehavior(Request $request)
{
    $validated = $request->validate([
        'cookie_consent' => 'required|string',
        'time_spent' => 'required|integer',
        'page' => 'required|string',
        'user_name' => 'required|string',
        'date' => 'required|date',
    ]);

    UserPreference::create([
        'user_id' => Auth::id(),
        'guest_id' => session()->getId(),
        'cookie_consent' => $validated['cookie_consent'],
        'time_spent' => $validated['time_spent'],
        'page' => $validated['page'],
        'user_name' => $validated['user_name'],
        'date' => $validated['date'],
    ]);

    return response()->json(['message' => 'Stored']);
}











public function showUserJourney($identifier)
{
    $user = null;

    if (Str::startsWith($identifier, 'guest-')) {
        $guestId = Str::replaceFirst('guest-', '', $identifier);
        $logs = UserPreference::where('guest_id', $guestId)->orderBy('created_at')->get();
        $userLabel = 'Guest: ' . $guestId;
    } else {
        $email = urldecode($identifier);
        $logs = UserPreference::with('user')->whereHas('user', function ($q) use ($email) {
            $q->where('email', $email);
        })->orderBy('created_at')->get();
        $user = $logs->first()?->user;
        $userLabel = $email;
    }

    $totalTimeSpent = $logs->sum('time_spent');
    $firstVisit = $logs->first()?->created_at;
    $lastVisit = $logs->last()?->created_at;
    $uniquePagesCount = $logs->pluck('page')->filter()->unique()->count();
    $cartVisits = $logs->filter(fn ($log) => str_contains(parse_url($log->page, PHP_URL_PATH) ?: '', '/cart'))->count();
    $checkoutVisits = $logs->filter(fn ($log) => str_contains(parse_url($log->page, PHP_URL_PATH) ?: '', '/checkout'))->count();

    $bookIds = $logs->map(function ($log) {
        $path = parse_url($log->page, PHP_URL_PATH) ?: '';
        return preg_match('/\/books\/.*\/(\d+)/', $path, $matches) ? (int) $matches[1] : null;
    })->filter()->unique()->values();

    $newsIds = $logs->map(function ($log) {
        $path = parse_url($log->page, PHP_URL_PATH) ?: '';
        return preg_match('/\/full_news\/.*\/(\d+)/', $path, $matches) ? (int) $matches[1] : null;
    })->filter()->unique()->values();

    $books = Book::whereIn('id', $bookIds)->get()->keyBy('id');
    $news = BookNews::whereIn('id', $newsIds)->get()->keyBy('id');

    $journey = $logs->values()->map(function ($log, $index) use ($logs, $books, $news) {
        $path = parse_url($log->page, PHP_URL_PATH) ?: '/';
        $query = parse_url($log->page, PHP_URL_QUERY) ?: '';
        parse_str($query, $queryParams);

        $type = 'other';
        $icon = 'bi-file-earmark-text';
        $title = $path === '/' ? 'მთავარი გვერდი' : (Str::afterLast(trim($path, '/'), '/') ?: $path);

        if (($queryParams['event'] ?? null) && str_starts_with($queryParams['event'], 'cart_')) {
            $type = 'cart_event';
            $icon = ($queryParams['event'] === 'cart_added') ? 'bi-cart-check' : 'bi-cart-x';
            $actionLabel = ($queryParams['event'] === 'cart_added') ? 'კალათაში დამატება' : 'კალათიდან ამოღება';
            $title = $actionLabel . ': ' . ($queryParams['title'] ?? (($queryParams['item_type'] ?? 'item') . ' #' . ($queryParams['item_id'] ?? '')));
        } elseif (str_contains($path, '/search')) {
            $type = 'search';
            $icon = 'bi-search';
            $title = 'ძებნა: ' . ($queryParams['title'] ?? 'საძიებო გვერდი');
        } elseif (preg_match('/\/books\/.*\/(\d+)/', $path, $matches)) {
            $type = 'book';
            $icon = 'bi-book';
            $title = $books->get((int) $matches[1])?->title ?? 'წიგნი #' . $matches[1];
        } elseif (preg_match('/\/full_news\/.*\/(\d+)/', $path, $matches)) {
            $type = 'article';
            $icon = 'bi-newspaper';
            $title = $news->get((int) $matches[1])?->title ?? 'სტატია #' . $matches[1];
        } elseif (str_contains($path, '/cart')) {
            $type = 'cart';
            $icon = 'bi-cart3';
            $title = 'კალათა';
        } elseif (str_contains($path, '/checkout') || str_contains($path, '/order')) {
            $type = 'checkout';
            $icon = 'bi-credit-card';
            $title = 'შეკვეთა / გადახდა';
        } elseif (str_contains($path, '/authors')) {
            $type = 'author';
            $icon = 'bi-person-lines-fill';
            $title = 'ავტორის გვერდი';
        } elseif (str_contains($path, '/genres') || str_contains($path, '/category')) {
            $type = 'category';
            $icon = 'bi-grid';
            $title = 'კატეგორია / ჟანრი';
        }

        $previous = $index > 0 ? $logs[$index - 1] : null;
        $next = $logs->get($index + 1);

        return (object) [
            'log' => $log,
            'path' => $path,
            'title' => $title,
            'type' => $type,
            'icon' => $icon,
            'previous_path' => $previous ? (parse_url($previous->page, PHP_URL_PATH) ?: $previous->page) : null,
            'next_path' => $next ? (parse_url($next->page, PHP_URL_PATH) ?: $next->page) : null,
        ];
    });

    $topPages = $journey->groupBy('path')->map(function ($items, $path) {
        return (object) [
            'path' => $path,
            'title' => $items->first()->title,
            'visits' => $items->count(),
            'time_spent' => $items->sum(fn ($item) => (int) $item->log->time_spent),
        ];
    })->sortByDesc('time_spent')->take(8)->values();

    $cart = $user
        ? Cart::with(['cartItems.book.author', 'cartItems.bundle'])->where('user_id', $user->id)->first()
        : null;

    $orders = $user
        ? Order::with(['orderItems.book.author', 'orderItems.bundle'])->where('user_id', $user->id)->latest()->take(8)->get()
        : collect();

    return view('admin.user_preferences.journey', compact(
        'logs',
        'journey',
        'topPages',
        'user',
        'userLabel',
        'totalTimeSpent',
        'firstVisit',
        'lastVisit',
        'uniquePagesCount',
        'cartVisits',
        'checkoutVisits',
        'cart',
        'orders'
    ));
}


    
    

    
    

public function showUserPreferencesWithPurchases(Request $request)
{
    $query = UserPreference::with('user')->latest();

    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    if (in_array($request->input('consent'), ['accepted', 'rejected', 'not_given'], true)) {
        $query->where('cookie_consent', $request->input('consent'));
    }

    $rawVisits = $query->get();
    $userIds = $rawVisits->pluck('user_id')->filter()->unique()->values();

    $ordersByUser = Order::whereIn('user_id', $userIds)
        ->latest()
        ->get()
        ->groupBy('user_id');

    $orderTotalsByUser = Order::selectRaw('user_id, COUNT(*) as orders_count, COALESCE(SUM(total), 0) as total_spent, MAX(created_at) as last_order_at')
        ->whereIn('user_id', $userIds)
        ->groupBy('user_id')
        ->get()
        ->keyBy('user_id');

    // Group by user email or guest ID
    $grouped = $rawVisits->groupBy(function ($item) {
        return $item->user?->email ?? 'guest-' . $item->guest_id;
    });

    // Map to summary data
    $summaries = $grouped->map(function ($entries, $identifier) use ($ordersByUser, $orderTotalsByUser) {
        $latest = $entries->first();
        $user = $latest->user;
        $orderSummary = $user ? $orderTotalsByUser->get($user->id) : null;
        $userOrders = $user ? $ordersByUser->get($user->id, collect()) : collect();
        $latestOrder = $userOrders->first();
        $pages = $entries->pluck('page')->filter();
        $topPage = $pages->count()
            ? $pages->countBy()->sortDesc()->keys()->first()
            : null;
        $orderContactText = $userOrders->flatMap(function ($order) {
            return [
                $order->name,
                $order->email,
                $order->phone,
                $order->city,
                $order->address,
                $order->order_id,
            ];
        })->filter()->unique()->implode(' ');

        return (object)[
            'identifier' => urlencode($identifier),
            'label' => Str::startsWith($identifier, 'guest-')
                ? 'Guest: ' . Str::replaceFirst('guest-', '', $identifier)
                : ($user?->name ? $user->name . ' - ' . $identifier : $identifier),
            'name' => $user?->name ?: $latestOrder?->name,
            'email' => $user?->email,
            'phone' => $user?->phone,
            'address' => $user?->address ?: $latestOrder?->address,
            'city' => $latestOrder?->city,
            'order_contact_text' => $orderContactText,
            'type' => $user ? 'registered' : 'guest',
            'cookie_consent' => $latest->cookie_consent,
            'latest_page' => $latest->page,
            'top_page' => $topPage,
            'total_time_spent' => $entries->sum('time_spent'),
            'average_time_spent' => round($entries->avg('time_spent') ?? 0),
            'date' => $latest->created_at->format('Y-m-d H:i'),
            'visits' => $entries->count(),
            'unique_pages' => $pages->unique()->count(),
            'orders_count' => (int) ($orderSummary->orders_count ?? 0),
            'total_spent' => (float) ($orderSummary->total_spent ?? 0),
            'last_order_at' => $orderSummary?->last_order_at,
        ];
    })->values();

    if ($request->filled('type') && in_array($request->input('type'), ['registered', 'guest'], true)) {
        $summaries = $summaries->where('type', $request->input('type'))->values();
    }

    if ($request->filled('search')) {
        $search = Str::lower($request->input('search'));

        $summaries = $summaries->filter(function ($item) use ($search) {
            return Str::contains(Str::lower(implode(' ', array_filter([
                $item->label,
                $item->name,
                $item->email,
                $item->phone,
                $item->address,
                $item->city,
                $item->order_contact_text,
                $item->latest_page,
                $item->top_page,
            ]))), $search);
        })->values();
    }

    $totals = [
        'users' => $summaries->count(),
        'registered' => $summaries->where('type', 'registered')->count(),
        'guests' => $summaries->where('type', 'guest')->count(),
        'visits' => $summaries->sum('visits'),
        'unique_pages' => $summaries->sum('unique_pages'),
        'time_spent' => $summaries->sum('total_time_spent'),
        'orders' => $summaries->sum('orders_count'),
        'revenue' => $summaries->sum('total_spent'),
    ];

    // Paginate manually
    $perPage = 20;
    $page = $request->input('page', 1);
    $pagedData = $summaries->forPage($page, $perPage);
    $userPreferences = new LengthAwarePaginator(
        $pagedData,
        $summaries->count(),
        $perPage,
        $page,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    // Chart counts
    $acceptedCount = $summaries->where('cookie_consent', 'accepted')->count();
    $rejectedCount = $summaries->where('cookie_consent', 'rejected')->count();
    $notGivenCount = $summaries->where('cookie_consent', 'not_given')->count();

    return view('admin.user_preferences_with_purchases', compact(
        'userPreferences',
        'acceptedCount',
        'rejectedCount',
        'notGivenCount',
        'totals'
    ));
}




public function userPathChartData()
{
    $paths = UserPreference::select('page')
        ->whereNotNull('page')
        ->groupBy('page')
        ->selectRaw('count(*) as count, page')
        ->orderByDesc('count')
        ->limit(10)
        ->get();

        $paths = $paths->map(function ($item) {
            $item->page = parse_url($item->page, PHP_URL_PATH) ?: $item->page;
            return $item;
        });
        
        return response()->json($paths);
}


    
}
