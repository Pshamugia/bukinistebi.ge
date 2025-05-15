<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
    if (Str::startsWith($identifier, 'guest-')) {
        $guestId = Str::replaceFirst('guest-', '', $identifier);
        $logs = UserPreference::where('guest_id', $guestId)->orderByDesc('id')->get();
        $userLabel = 'Guest: ' . $guestId;
    } else {
        $email = urldecode($identifier);
        $logs = UserPreference::whereHas('user', function ($q) use ($email) {
            $q->where('email', $email);
        })->orderByDesc('id')->get();
        $userLabel = $email;
    }

    $totalTimeSpent = $logs->sum('time_spent');

    return view('admin.user_preferences.journey', compact('logs', 'userLabel', 'totalTimeSpent'));
}


    
    

    
    

public function showUserPreferencesWithPurchases(Request $request)
{
    $rawVisits = UserPreference::with('user')->latest()->get();

    // Group by user email or guest ID
    $grouped = $rawVisits->groupBy(function ($item) {
        return $item->user?->email ?? 'guest-' . $item->guest_id;
    });

    // Map to summary data
    $summaries = $grouped->map(function ($entries, $identifier) {
        $latest = $entries->first();

        return (object)[
            'identifier' => urlencode($identifier),
            'label' => Str::startsWith($identifier, 'guest-')
                ? 'Guest: ' . Str::replaceFirst('guest-', '', $identifier)
                : $identifier,
            'cookie_consent' => $latest->cookie_consent,
            'page' => $latest->page,
            'time_spent' => $latest->time_spent,
            'total_time_spent' => $entries->sum('time_spent'), // ✅ this is what was missing
            'date' => $latest->created_at->format('Y-m-d H:i'),
            'visits' => $entries->count(),
        ];
    })->values();

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
    $acceptedCount = $rawVisits->where('cookie_consent', 'accepted')->count();
    $rejectedCount = $rawVisits->where('cookie_consent', 'rejected')->count();

    return view('admin.user_preferences_with_purchases', compact(
        'userPreferences',
        'acceptedCount',
        'rejectedCount'
    ));
}




public function userPathChartData()
{
    $paths = UserPreference::select('page')
        ->groupBy('page')
        ->selectRaw('count(*) as count, page')
        ->orderByDesc('count')
        ->limit(10)
        ->get();

        $paths = $paths->map(function ($item) {
            $item->page = parse_url($item->page, PHP_URL_PATH); // Keep only the path (e.g. /books/123)
            return $item;
        });
        
        return response()->json($paths);
}


    
}
