<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Auction;
use Illuminate\Http\Request;
use App\Models\AuctionCategory;
use Illuminate\Support\Facades\Auth;

class AuctionSubmissionController extends Controller
{
    public function create()
    {
        $user = Auth::user();

        // Profile completeness check
        if (empty($user->phone) || empty($user->address)) {
            return redirect()
                ->route('account.edit')
                ->with('error', 'აუქციონის შექმნამდე გთხოვთ შეავსოთ ტელეფონი და მისამართი.');
        }

$categories = AuctionCategory::all();


        return view('auction.submit', compact('categories'));
    }




    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'auction_category_id' => 'required|exists:auction_categories,id',
            'start_price' => 'required|numeric|min:1',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'video' => [
                'nullable',
                'url',
                'regex:/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\//'
            ],


            'min_bid'     => 'nullable|numeric|min:0',
            'max_bid'     => 'nullable|numeric|gt:min_bid',
            'is_free_bid' => 'nullable|boolean',

            // photos validation
            'photos' => 'required|array|min:1|max:4',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // Create book
        $book = Book::create([
            'title' => $request->title,
            'description' => $request->description,

            // REQUIRED FIELDS – SYSTEM DEFAULTS
            'price' => 0,                // real price comes from auction
            'quantity' => 1,             // irrelevant for auctions
            'author_id' => null,         // or a system “Unknown” author ID
            'photo' => null,             // gallery is used instead

            'auction_only' => true,
        ]);


        // Save photos
        foreach ($request->file('photos') as $photo) {
            if (!$photo) continue;

            $path = $photo->store('books', 'public');

            \App\Models\BookImage::create([
                'book_id' => $book->id,
                'path' => $path,
            ]);
        }

        // Create auction (pending approval)
        Auction::create([
            'book_id' => $book->id,
            'user_id' => auth()->id(),
            'auction_category_id' => $request->auction_category_id,
            'start_price' => $request->start_price,
            'current_price' => $request->start_price,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'video' => $request->video,
            'min_bid'       => $request->min_bid,
            'max_bid'       => $request->max_bid,
            'is_free_bid'   => $request->has('is_free_bid'),
            'is_approved' => false,
        ]);

        return redirect()
            ->route('auction.index')
            ->with('success', '✔ თქვენი აუქციონი გაიგზავნა დასამტკიცებლად.');
    }
}
