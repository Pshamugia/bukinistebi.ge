<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/Auction.php
class Auction extends Model
{
    protected $fillable = ['book_id', 'start_price', 'current_price', 'start_time', 'end_time', 'is_active', 'winner_id', 'min_bid', 'max_bid', 'is_free_bid'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function paidUsers()
    {
        return $this->belongsToMany(User::class, 'auction_users')
            ->withPivot('paid_at')
            ->withTimestamps();
    }
}
