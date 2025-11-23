<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/Bid.php
class Bid extends Model
{
    public $timestamps = false;

    protected $fillable = ['auction_id', 'user_id', 'amount', 'created_at', 'is_anonymous'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function auction() {
        return $this->belongsTo(Auction::class);
    }

    protected $casts = [
    'is_anonymous' => 'boolean',
];

}
