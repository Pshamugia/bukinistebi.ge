<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'title', 'author', 'publishing_year', 'comment', 'email',
        // 'is_done' not required in fillable (we set it in controller), but ok to include:
        'is_done',
    ];

    protected $casts = [
        'is_done' => 'boolean',
    ];
}
