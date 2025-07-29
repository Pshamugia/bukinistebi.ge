<?php

namespace App\Models;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'name_en'];

    // Relationships

    /**
     * Get the books for the author.
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
