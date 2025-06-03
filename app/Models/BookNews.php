<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookNews extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'description',
        'full',
        'title_en', 
        'description_en', 
        'full_en',
    ];
}
