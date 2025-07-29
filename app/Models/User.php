<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'address',
        'phone',
        'iban',
    ];

    // Relationship with the Cart model (one cart per user)
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    // Relationship with the Order model (a user can have many orders)
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isRole($role)
    {
        return $this->role === $role;
    }
    public function books()
    {
        return $this->hasMany(Book::class, 'uploader_id');
    }

    public function userPreference()
    {
        return $this->hasOne(UserPreference::class);
    }


    public function wonAuctions()
    {
        return $this->hasMany(Auction::class, 'winner_id');
    }


    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function paidAuctions()
    {
        return $this->belongsToMany(Auction::class, 'auction_users')
            ->withPivot('paid_at')
            ->withTimestamps();
    }


    public function startedAuctionPayment($auctionId)
    {
        return $this->paidAuctions()
            ->where('auction_id', $auctionId)
            ->whereNotNull('auction_users.created_at')
            ->exists();
    }

    public function paidAuction($auctionId)
    {
        return $this->paidAuctions()
            ->where('auction_id', $auctionId)
            ->whereNotNull('auction_users.paid_at')
            ->exists();
    }
}
