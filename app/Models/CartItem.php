<?php

namespace App\Models;
use App\Models\Cart;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'book_id',
        'quantity',
        'price',
    ];

    // Relationships

    /**
     * Get the cart that owns the cart item.
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the book associated with the cart item.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
