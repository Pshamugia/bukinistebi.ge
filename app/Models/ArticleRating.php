<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleRating extends Model
{
    use HasFactory;

    // Specify the table name (if not following Laravel's convention)
    protected $table = 'article_ratings';

    // Define the fillable fields
    protected $fillable = [
        'book_id',
        'user_id',
        'rating',
    ];

    // Optionally, define relationships
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
