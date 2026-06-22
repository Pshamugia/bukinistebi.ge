<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publishing extends Model
{
    protected $table = 'publishing';

    protected $fillable = [
        'title',
        'description',
        'category',
        'shop_url',
        'image_1',
        'image_2',
        'image_3',
        'image_4'
    ];
}