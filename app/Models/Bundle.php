<?php

 
namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class Bundle extends Model
{

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

 

    public function getImageUrlAttribute()
    {
        if (!$this->image) return null;

        // if it's already a full URL, return as-is
        if (Str::startsWith($this->image, ['http://','https://','/storage/'])) {
            return $this->image;
        }
        // otherwise it's a path on the 'public' disk
        return Storage::url($this->image); // e.g. /storage/bundles/abc.jpg
    }

    
    protected $fillable = [
        'title','slug','price','original_price','active','description','image','starts_at','ends_at'
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (empty($m->slug)) {
                $m->slug = Str::slug($m->title) . '-' . Str::random(6);
            }
        });
    }

   // app/Models/Bundle.php
   public function books()
   {
       return $this->belongsToMany(Book::class, 'bundle_book', 'bundle_id', 'book_id')
           ->withPivot('qty'); // no timestamps on this pivot
   }
   

    

    public function scopeActive($q)
    {
        return $q->where('active', true)
                 ->when(now(), function ($qq) {
                     $qq->where(function ($w) {
                         $w->whereNull('starts_at')->orWhere('starts_at', '<=', now());
                     })->where(function ($w) {
                         $w->whereNull('ends_at')->orWhere('ends_at', '>=', now());
                     });
                 });
    }


    public function availableQuantity(): int
{
    // Try relation first
    $this->loadMissing('books');

    $rows = $this->books;
    if ($rows->isNotEmpty()) {
        $min = PHP_INT_MAX;
        foreach ($rows as $b) {
            $need = max(1, (int) ($b->pivot->qty ?? 1));
            $have = (int) ($b->quantity ?? 0);
            $min  = min($min, intdiv($have, $need));
        }
        return $min === PHP_INT_MAX ? 0 : $min;
    }

    // Fallback (direct SQL) – ensures correctness even if relation didn't load
    $rows = DB::table('bundle_book')
        ->join('books', 'books.id', '=', 'bundle_book.book_id')
        ->where('bundle_book.bundle_id', $this->id)
        ->get(['books.quantity as stock', 'bundle_book.qty as need']);

    if ($rows->isEmpty()) {
        return 0;
    }

    $min = PHP_INT_MAX;
    foreach ($rows as $r) {
        $need = max(1, (int) $r->need);
        $have = (int) $r->stock;
        $min  = min($min, intdiv($have, $need));
    }
    return $min === PHP_INT_MAX ? 0 : $min;
}

    


public function getSavingsAttribute(): int
{
    return max(0, (int)$this->original_price - (int)$this->price);
}

 


}
