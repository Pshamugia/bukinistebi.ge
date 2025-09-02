<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'book_id',
        'bundle_id',   // <- add if the column exists (optional)
        'quantity',
        'price',
        'size',
        'meta',        // <- allow saving meta JSON
    ];


     // so $item->meta returns an array
     protected $casts = [
        'meta' => 'array',
    ];
    
    // Relationship with the Order model
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function book()
    {
        return $this->belongsTo(Book::class);
    }


    public function bundle(){ return $this->belongsTo(\App\Models\Bundle::class); }

    
}
