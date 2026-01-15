<?php

namespace App\Http\Controllers\Admin;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SearchKeyword;
use App\Models\UserPreference;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PublishersAggregateExport;
use App\Exports\PublisherSalesExport;
use App\Exports\PublisherTitlesExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log; // Use the full namespace for Log
use Carbon\Carbon;


class AdminPublisherController extends Controller
{
    
    
    public function activity(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    $publishers = User::where('role', 'publisher')
    ->with(['books.orderItems' => function ($query) use ($startDate, $endDate) {
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
    }])
    ->get()
    ->map(function ($publisher) use ($startDate, $endDate) {
        $publisher->total_earned = $publisher->books->sum(function ($book) use ($startDate, $endDate) {
            return $book->orderItems
                ->filter(function ($item) use ($startDate, $endDate) {
                    if ($startDate && $endDate) {
                        return $item->created_at >= $startDate && $item->created_at <= $endDate;
                    }
                    return true;
                })
                ->sum(function ($item) {
                    return $item->price * $item->quantity;
                });
        });
    
        $publisher->total_sold_quantity = $publisher->books->sum(function ($book) use ($startDate, $endDate) {
            return $book->orderItems
                ->filter(function ($item) use ($startDate, $endDate) {
                    if ($startDate && $endDate) {
                        return $item->created_at >= $startDate && $item->created_at <= $endDate;
                    }
                    return true;
                })
                ->sum('quantity');
        });
    
        return $publisher;
    })
    ->sortByDesc(fn($publisher) => $publisher->books->first()->created_at ?? now()->subYears(10));

    return view('admin.publishers.activity', compact('publishers', 'startDate', 'endDate'));
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
    


    public function exportAllPublishers(Request $request)
{
    $start = $request->query('start_date');
    $end   = $request->query('end_date');

    return Excel::download(
        new PublishersAggregateExport($start, $end),
        'publishers_aggregate_'.$start.'_'.$end.'.xlsx'
    );
}

public function exportPublisher(Request $request, User $publisher)
{
    abort_unless($publisher->role === 'publisher', 404);

    $start = $request->query('start_date');
    $end   = $request->query('end_date');

    $publisherName = $this->georgianToLatin($publisher->name);

    return Excel::download(
        new PublisherSalesExport($publisher->id, $start, $end),
        "{$publisherName}_sales_{$start}_{$end}.xlsx"
    );
}




private function georgianToLatin(string $text): string
{
    $map = [
        'ა'=>'a','ბ'=>'b','გ'=>'g','დ'=>'d','ე'=>'e','ვ'=>'v','ზ'=>'z','თ'=>'t',
        'ი'=>'i','კ'=>'k','ლ'=>'l','მ'=>'m','ნ'=>'n','ო'=>'o','პ'=>'p','ჟ'=>'zh',
        'რ'=>'r','ს'=>'s','ტ'=>'t','უ'=>'u','ფ'=>'f','ქ'=>'k','ღ'=>'gh','ყ'=>'q',
        'შ'=>'sh','ჩ'=>'ch','ც'=>'ts','ძ'=>'dz','წ'=>'ts','ჭ'=>'ch','ხ'=>'kh',
        'ჯ'=>'j','ჰ'=>'h',
    ];

    $text = mb_strtolower($text);
    $text = strtr($text, $map);
    $text = preg_replace('/[^a-z0-9]+/', '_', $text);
    $text = trim($text, '_');

    return $text;
}

public function exportPublisherTitles(Request $request, User $publisher)
{
    abort_unless($publisher->role === 'publisher', 404);

    $start = $request->query('start_date');
    $end   = $request->query('end_date');

    $publisherName = $this->georgianToLatin($publisher->name);

    return Excel::download(
        new PublisherTitlesExport($publisher->id, $start, $end),
        "{$publisherName}_sold_{$start}_{$end}.xlsx"
    );
}






}