<?php

namespace App\Models;

use App\Models\Author;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\BookImage;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'language',
        'title',
        'thumb_image', 
        'photo',
        'video',
        'photo_2',
        'photo_3',
        'photo_4',
        'description',
        'full',
        'category_id',
        'genre_id',
        'author_id',
        'price',
        'new_price',
        'views',
        'quantity',
        'status',
        'publishing_date',
        'pages',
        'cover',
        'hide',
        'uploader_id',
        'manual_created_at',
        'auction_only',
        'size',
        'condition',   
    ];

    // Relationships

    /**
     * Get the author of the book.
     */

    protected $dates = ['manual_created_at'];  // To make sure it's treated as a date


    public function images()
{
    return $this->hasMany(BookImage::class);
}



    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * Get the category of the book.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the cart items associated with the book.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the order items associated with the book.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function ratings()
    {
        return $this->hasMany(ArticleRating::class, 'book_id');
    }


   public function genres()
{
    return $this->belongsToMany(Genre::class, 'book_genre');
}


public function publisher()
{
    return $this->belongsTo(User::class, 'uploader_id')->where('role', 'publisher');
}


public function auction()
{
    return $this->hasOne(Auction::class);
}


 
// add inverse relation (keep your existing fields)
public function bundles()
{
    return $this->belongsToMany(\App\Models\Bundle::class, 'bundle_book')->withPivot('qty');
}



}
