<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
protected $fillable = [
    'book_id',
    'user_id',
    'start_price',
    'current_price',
    'start_time',
    'end_time',
    'is_active',
    'winner_id',
    'min_bid',
    'max_bid',
    'is_free_bid',
    'is_approved',
    'approved_at',
];



public function getEffectiveCurrentPriceAttribute()
{
    return $this->bids()->exists()
        ? $this->current_price
        : $this->start_price;
}

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

    public function user()
{
    return $this->belongsTo(User::class);
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
