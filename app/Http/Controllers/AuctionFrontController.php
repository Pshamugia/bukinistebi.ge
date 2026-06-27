<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Auction;
use Illuminate\Http\Request;
use App\Models\AuctionCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuctionFrontController extends Controller
{

 
public function index(Request $request)
{
    $categories = AuctionCategory::orderBy('name')->get();

   $auctions = Auction::with(['book', 'auctionCategory'])
      ->where('is_approved', true)
        ->where('is_active', true)
        ->where('end_time', '>', now())
    ->when(request('category'), function ($q) {
        $q->whereHas('auctionCategory', function ($q2) {
            $q2->where('slug', request('category'));
        });
    })
    ->latest()
    ->paginate(12);

    return view('auction.index', compact('auctions', 'categories'));
}





    public function show(Auction $auction)
    {
        $auction->load('book', 'bids.user');

        return view('auction.show', compact('auction'));
    }

    public function bid(Request $request, Auction $auction)
    {
        $bidAmount = $request->bid_amount;



        if (empty(auth()->user()->phone) || empty(auth()->user()->address)) {
            return response()->json([
                'missing_fields' => true
            ]);
        }

        // ✅ Check if user has paid the auction participation fee
        if (!Auth::user()->paidAuction($auction->id)) {
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

        $highestBid = $auction->bids()->max('amount');
        $basePrice = $highestBid ?? $auction->start_price;

        if ($bidAmount <= $basePrice) {
            return back()->withErrors([
                'bid_amount' => "ბიჯი უნდა იყოს მეტი ვიდრე მიმდინარე ფასი ({$basePrice} ₾)."
            ]);
        }


        $isAnonymous = $request->has('is_anonymous'); // ✅ define the variable

        Bid::create([
            'auction_id' => $auction->id,
            'user_id' => Auth::id(),
            'amount' => $bidAmount,
            'is_anonymous' => $isAnonymous,
            'created_at' => now(),
        ]);


        $auction->current_price = $bidAmount;
        $auction->save();

        return back()->with('success', 'თქვენი ბიჯი წარმატებით დაემატა!');
    }




    public function buyNow(Request $request, Auction $auction)
    {
        if (empty(auth()->user()->phone) || empty(auth()->user()->address)) {
            return back()->withErrors([
                'buy_now' => 'ბლიც-ფასით ყიდვისთვის გთხოვთ შეავსოთ ტელეფონი და მისამართი პროფილში.',
            ]);
        }

        $auction = DB::transaction(function () use ($auction) {
            $lockedAuction = Auction::whereKey($auction->id)->lockForUpdate()->firstOrFail();

            if ($lockedAuction->buy_now_reserved_until && $lockedAuction->buy_now_reserved_until->isPast() && !$lockedAuction->is_paid) {
                $lockedAuction->update([
                    'buy_now_user_id' => null,
                    'buy_now_reserved_until' => null,
                    'is_active' => $lockedAuction->is_approved && $lockedAuction->start_time <= now() && $lockedAuction->end_time > now(),
                ]);

                $lockedAuction->refresh();
            }

            if ($lockedAuction->buy_now_user_id && $lockedAuction->buy_now_reserved_until && $lockedAuction->buy_now_reserved_until->isFuture()) {
                if ((int) $lockedAuction->buy_now_user_id !== Auth::id()) {
                    return null;
                }

                $lockedAuction->update([
                    'buy_now_reserved_until' => now()->addMinutes(15),
                ]);

                return $lockedAuction->fresh('book');
            }

            if (!$lockedAuction->is_approved || !$lockedAuction->is_active || $lockedAuction->end_time <= now()) {
                return null;
            }

            if ($lockedAuction->buy_now_price === null) {
                return null;
            }

            $effectiveCurrentPrice = $lockedAuction->bids()->max('amount') ?? $lockedAuction->start_price;

            if ($effectiveCurrentPrice >= $lockedAuction->buy_now_price) {
                return null;
            }

            if ($lockedAuction->buy_now_user_id && (int) $lockedAuction->buy_now_user_id !== Auth::id()) {
                return null;
            }

            $lockedAuction->update([
                'buy_now_user_id' => Auth::id(),
                'buy_now_reserved_until' => now()->addMinutes(15),
                'is_active' => false,
            ]);

            return $lockedAuction->fresh('book');
        });

        if (!$auction || (int) $auction->buy_now_user_id !== Auth::id()) {
            return back()->withErrors([
                'buy_now' => 'აუქციონი უკვე დასრულებულია, დაჯავშნილია ან ბლიც-ფასი აღარ არის ხელმისაწვდომი.',
            ]);
        }

        $request->merge(['auction_id' => $auction->id]);

        return app(TbcCheckoutController::class)->initializeAuctionPayment($request);
    }

    public function getBids(Auction $auction)
    {
        $auction->load('bids.user');

        return view('auction.partials.bid_history', compact('auction'));
    }

    public function rules()
    {
        return view('auction.rules');  // ✅ this matches resources/views/auction/rules.blade.php
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
