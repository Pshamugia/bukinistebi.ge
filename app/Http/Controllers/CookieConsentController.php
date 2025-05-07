<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CookieConsentController extends Controller
{
    // Display user preferences
    public function index(Request $request)
{
    $query = UserPreference::with('user')->orderBy('created_at', 'desc');

    // Filter if needed
    if ($request->has('consent') && $request->consent !== 'all') {
        $query->where('cookie_consent', $request->consent);
    }

    // Paginate results
    $userPreferences = $query->paginate(20);

    // Add these two lines ↓↓↓
    $acceptedCount = UserPreference::where('cookie_consent', 'accepted')->count();
    $rejectedCount = UserPreference::where('cookie_consent', 'rejected')->count();

    return view('admin.user_preferences_with_purchases', compact(
        'userPreferences',
        'acceptedCount',
        'rejectedCount'
    ));
}

    

    
    
    
    
    
    // Store user behavior (including cookie consent)
    public function storeUserBehavior(Request $request)
{
    Log::info('Received request:', $request->all());

    $validated = $request->validate([
        'cookie_consent' => 'required|string',
        'time_spent' => 'required|integer',
        'page' => 'required|string',
        'user_name' => 'required|string',
        'date' => 'required|date',
    ]);

    Log::info('Validated data:', $validated);

    UserPreference::create([
        'user_id' => Auth::id(),
        'guest_id' => session()->getId(),
        'cookie_consent' => $validated['cookie_consent'],
        'time_spent' => $validated['time_spent'],
        'page' => $validated['page'],
        'user_name' => $validated['user_name'],
        'date' => $validated['date'],
    ]);

    return response()->json(['message' => 'User behavior data stored successfully.']);
}



    
    

    
    

public function showUserPreferencesWithPurchases()
{
    $userPreferences = UserPreference::with('user')->paginate(10);

    $acceptedCount = UserPreference::where('cookie_consent', 'accepted')->count();
    $rejectedCount = UserPreference::where('cookie_consent', 'rejected')->count();

    return view('admin.user_preferences_with_purchases', compact(
        'userPreferences',
        'acceptedCount',
        'rejectedCount'
    ));
}

    
}
