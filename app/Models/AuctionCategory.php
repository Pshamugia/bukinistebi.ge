<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuctionCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];

    public function auctions()
    {
        return $this->hasMany(Auction::class);
    }
}

