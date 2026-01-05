<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookImage extends Model
{
    protected $fillable = ['book_id', 'path'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
