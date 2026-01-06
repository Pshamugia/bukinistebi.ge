<?php
 

namespace App\Http\Controllers\Admin;

use App\Models\Book;
use App\Models\Auction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuctionController extends Controller
{
public function index()
{
    $auctions = Auction::with(['book', 'user'])
        ->latest()
        ->paginate(10);

    return view('admin.auctions.index', compact('auctions'));
}



    public function create()
    {
        $books = Book::whereDoesntHave('auction')->get(); // Only books not in auctions
        $books = Book::with('author')->where('auction_only', true)->get();

        return view('admin.auctions.create', compact('books'));
    }

    public function store(Request $request)
{
    $request->validate([
        'book_id' => 'required|exists:books,id',
        'start_price' => 'required|numeric|min:0',
        'start_time' => 'required|date',
        'end_time' => 'required|date|after:start_time',
        'min_bid' => 'nullable|numeric|min:0',
        'max_bid' => 'nullable|numeric|gt:min_bid',
        'is_free_bid' => 'nullable|boolean',
    ]);

    Auction::create([
        'book_id' => $request->book_id,
        'user_id'       => Auth::id(), 
        'start_price' => $request->start_price,
        'current_price' => $request->start_price,
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'min_bid' => $request->min_bid,
        'max_bid' => $request->max_bid,
        'is_free_bid' => $request->has('is_free_bid'),
    ]);

    return redirect()->route('admin.auctions.index')->with('success', 'Auction created successfully.');
}



public function edit(Auction $auction)
{
    $books = \App\Models\Book::with('author') // include author
        ->where('auction_only', true)
        ->get();

    return view('admin.auctions.edit', compact('auction', 'books'));
}



public function update(Request $request, Auction $auction)
{
    $request->validate([
        'book_id' => 'required|exists:books,id',
        'start_price' => 'required|numeric|min:0',
        'start_time' => 'required|date',
        'end_time' => 'required|date|after:start_time',
        'min_bid' => 'nullable|numeric|min:0',
        'max_bid' => 'nullable|numeric|gt:min_bid',
        'is_free_bid' => 'nullable|boolean',
    ]);

    $auction->update([
        'book_id' => $request->book_id,
        'start_price' => $request->start_price,
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'min_bid' => $request->min_bid,
        'max_bid' => $request->max_bid,
        'is_free_bid' => $request->has('is_free_bid'),
    ]);

    return redirect()->route('admin.auctions.index')->with('success', 'Auction updated successfully.');
}


public function destroy(Auction $auction)
{
    // If auction has bids, optional protection:
    if ($auction->bids()->count() > 0) {
        return back()->with('error', '❌ ვერ წაშლიდა — აუქციონს უკვე აქვს ბიჯები.');
    }

    // Delete auction
    $auction->delete();

    return redirect()
        ->route('admin.auctions.index')
        ->with('success', '✔ აუქციონი წარმატებით წაიშალა!');
}



public function bidsPartial($id)
{
    $auction = Auction::with('bids.user')->findOrFail($id);
    return view('auction.partials.bid_history', compact('auction'));
}



public function userDashboard()
{
    $user = Auth::user();

    $activeBids = $user->bids()->with('auction.book')->latest()->get();
    $wonAuctions = $user->wonAuctions()->with('book')->get(); // custom relationship

    return view('auction.dashboard', compact('activeBids', 'wonAuctions'));
}


public function participants()
{
    $auctions = \App\Models\Auction::with(['book', 'bids.user', 'winner'])
        ->orderBy('end_time', 'desc')
        ->get();

    return view('admin.auctions.participants', compact('auctions'));
}

public function approve(Auction $auction)
{
    $auction->update([
        'is_approved' => true,
        'is_active'   => true, // activate on approval
    ]);

    return back()->with('success', 'აუქციონი დამტკიცდა და გააქტიურდა.');
}



}

