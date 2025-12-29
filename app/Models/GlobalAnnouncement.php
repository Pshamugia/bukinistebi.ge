<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalAnnouncement extends Model
{
    protected $fillable = [
        'title','message','is_active','starts_at','ends_at', 'recurrence_type', 'recurrence_time'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
        'is_active' => 'boolean'
    ];
}
