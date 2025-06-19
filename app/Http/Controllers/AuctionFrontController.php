<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuctionFrontController extends Controller
{


    public function index()
    {
        $auctions = Auction::with('book')
            ->where('is_active', true)
            ->where('end_time', '>', now()) // ✅ this is hiding expired auctions
            ->orderBy('end_time')
            ->paginate(10);

        return view('auction.index', compact('auctions'));
    }



    public function show(Auction $auction)
    {
        $auction->load('book', 'bids.user');

        return view('auction.show', compact('auction'));
    }

    public function bid(Request $request, Auction $auction)
{
    $bidAmount = $request->bid_amount;

    // ✅ Check if user has paid the auction participation fee
    if (!Auth::user()->has_paid_auction_fee) {
        return back()->withErrors(['bid_amount' => 'აუცილებელია აუქციონის სიმბოლური საფასურის გადახდა.']);
    }

    if (!$auction->is_active) {
        return back()->withErrors(['bid_amount' => 'აუქციონი დასრულებულია.']);
    }

    if (!$auction->is_free_bid) {
        if ($auction->min_bid !== null && $bidAmount < $auction->min_bid) {
            return back()->withErrors(['bid_amount' => "მინიმალური ბიჯი არის {$auction->min_bid} ₾."]);
        }

        if ($auction->max_bid !== null && $bidAmount > $auction->max_bid) {
            return back()->withErrors(['bid_amount' => "მაქსიმალური ბიჯი არის {$auction->max_bid} ₾."]);
        }

        if ($auction->min_bid !== null && $auction->max_bid !== null && $auction->min_bid == $auction->max_bid && $bidAmount != $auction->min_bid) {
            return back()->withErrors(['bid_amount' => "აუცილებელია ბიჯის ზომა იყოს正 {$auction->min_bid} ₾."]);
        }
    }

    if ($bidAmount <= $auction->current_price) {
        return back()->withErrors(['bid_amount' => 'ბიჯი უნდა იყოს მიმდინარე ფასზე მეტი.']);
    }

    Bid::create([
        'auction_id' => $auction->id,
        'user_id' => Auth::id(),
        'amount' => $bidAmount,
        'created_at' => now(),
    ]);

    $auction->current_price = $bidAmount;
    $auction->save();

    return back()->with('success', 'თქვენი ბიჯი წარმატებით დაემატა!');
}



    public function getBids(Auction $auction)
    {
        $auction->load('bids.user');

        return view('auction.partials.bid_history', compact('auction'));
    }


    public function myAuctionDashboard()
    {
        $userId = Auth::id();

        // 🏆 Won but unpaid
        $wonAuctions = Auction::with('book')
            ->where('winner_id', $userId)
            ->get();

        // 💳 Paid
        $paidAuctions = $wonAuctions->where('is_paid', true);

        // 📊 All bids
        $activeBids = Bid::with('auction.book')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        return view('auction.dashboard', compact(
            'wonAuctions',
            'paidAuctions',
            'activeBids'
        ));
    }




    public function closeExpiredAuctions()
    {
        $expiredAuctions = Auction::where('is_active', true)
            ->where('end_time', '<=', now())
            ->get();

        foreach ($expiredAuctions as $auction) {
            $highestBid = $auction->bids()->orderByDesc('amount')->first();

            if ($highestBid) {
                $auction->winner_id = $highestBid->user_id;
                $auction->current_price = $highestBid->amount;
            }

            $auction->is_active = false;
            $auction->save();
        }

        return 'Auctions checked and closed.';
    }


    public function payAuctionFee(Request $request)
{
    $user = Auth::user();
    $user->has_paid_auction_fee = 1;
    $user->save();

    return back()->with('success', 'გადახდა წარმატებით განხორციელდა! ახლა შეგიძლია ბიჯი.');
}

}
