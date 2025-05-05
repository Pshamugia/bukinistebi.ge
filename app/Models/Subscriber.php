<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    // Explicitly define the table name
    protected $table = 'subscribers';

    // Define fillable fields
    protected $fillable = ['email'];
}