<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    protected $fillable = [
        'user_id',
        'guest_id',
        'cookie_consent',
        'time_spent',
        'page',
        'user_name',
        'date', // ← ADD THIS
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
