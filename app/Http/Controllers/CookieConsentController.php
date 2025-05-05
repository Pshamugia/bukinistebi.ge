<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CookieConsentController extends Controller
{
    // Display user preferences
    public function index()
    {
        // Fetch all user preferences with the associated user (no need for purchase history)
        $userPreferences = UserPreference::with('user')->paginate(10);

        Log::info('User Preferences:', ['data' => $userPreferences]);

        return view('admin.user_preferences_with_purchases', compact('userPreferences'));
    }

    
    
    
    
    
    // Store user behavior (including cookie consent)
    public function storeUserBehavior(Request $request)
{
    // Log the incoming request for debugging
    Log::info('Received request:', $request->all());

    // Validate incoming data
    $validated = $request->validate([
        'cookie_consent' => 'required|string',
        'time_spent' => 'required|integer',
        'page' => 'required|string',
        'user_name' => 'required|string',
        'date' => 'required|date',
    ]);

    // Log validated data for debugging
    Log::info('Validated data:', $validated);

    // Check if user is logged in or guest
    $user = Auth::user();
    if ($user) {
        // If the user is logged in, store the data
        UserPreference::updateOrCreate(
            ['user_id' => $user->id], // Matching the user_id with the logged-in user
            $validated // The validated data to store
        );
    } else {
        // If the user is a guest, store the data using session ID (guest ID)
        $guestId = session()->getId();
        UserPreference::updateOrCreate(
            ['guest_id' => $guestId], // Matching the guest session ID
            $validated // The validated data to store
        );
    }

    return response()->json(['message' => 'User behavior data stored successfully.']);
}


    
    

    
    

    public function showUserPreferencesWithPurchases()
    {
        // Fetching the data with pagination
        $userPreferences = UserPreference::with('user')->paginate(10);
    
        return view('admin.user_preferences_with_purchases', compact('userPreferences'));
    }

    
}
