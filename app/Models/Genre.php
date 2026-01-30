<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;
 
    protected $fillable = ['name', 'name_en', 'name_ru',];

    public function books()
{
    return $this->belongsToMany(Book::class, 'book_genre');
}


 public function getLocalizedName(): string
    {
        return match (app()->getLocale()) {
            'ru' => $this->name_ru ?? $this->name_en ?? $this->name,
            'en' => $this->name_en ?? $this->name,
            default => $this->name,
        };
    }

    /**
     * Scope: available for current locale
     */
    public function scopeForLocale($q, ?string $locale = null)
    {
        $locale ??= app()->getLocale();

        return match ($locale) {
            'ru' => $q->whereNotNull('name_ru'),
            'en' => $q->whereNotNull('name_en'),
            default => $q->whereNotNull('name'),
        };
    }


    public function isSouvenir(): bool
{
    return in_array(
        mb_strtolower($this->name),
        ['სუვენირები', 'souvenirs', 'сувениры'],
        true
    );
}

}


