<?php

namespace App\Http\Controllers\Admin;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SearchKeyword;
use App\Models\UserPreference;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log; // Use the full namespace for Log
use Carbon\Carbon;


class AdminPublisherController extends Controller
{
    public function activity()
    {
        $publishers = User::where('role', 'publisher')->with('books')->get();
    
        // Log publisher data for debugging
        Log::info('Publishers and Books:', $publishers->toArray());
    
        return view('admin.publishers.activity', compact('publishers'));
    }


    public function toggleVisibility($id)
    {
        $book = Book::findOrFail($id);
    
        // Toggle the hide status
        $book->hide = !$book->hide;
        $book->save();
    
        return response()->json([
            'success' => true,
            'hide' => $book->hide,
            'message' => $book->hide ? 'Your book is hidden' : 'Your book is visible for website users',
        ]);
    }


    public function showUserKeywords(Request $request)
    {
        // Fetch user search keywords with pagination
        $keywords = SearchKeyword::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Use pagination for existing keywords
    
        // Get the 5 most searched keywords along with their counts
        $topKeywords = SearchKeyword::select('keyword')
        ->where('created_at', '>=', Carbon::now()->subMonth()) // Filter by last month
        ->groupBy('keyword')
        ->selectRaw('COUNT(*) as count') // Get the count of each keyword
        ->orderByRaw('COUNT(*) DESC') // Order by the count of each keyword
        ->limit(10) // Limit to top 10 keywords
        ->get(); // Execute the query

        SearchKeyword::where('created_at', '<', Carbon::now()->subMonth())->delete();

    
        return view('admin.user_keywords', compact('keywords', 'topKeywords'));
    }
    
    
    
 }

