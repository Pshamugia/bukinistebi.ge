<?php

namespace App\Models;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'name_en', 'name_ru'];

    // Relationships

    /**
     * Get the books for the author.
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }


    public function getLocalizedName(): string
{
    return match (app()->getLocale()) {
        'ru' => $this->name_ru ?: ($this->name_en ?: $this->name),
        'en' => $this->name_en ?: $this->name,
        default => $this->name,
    };
}

}
