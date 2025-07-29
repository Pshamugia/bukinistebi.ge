<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'title', 'author', 'publishing_year', 'comment', 'email',
    ];
}
