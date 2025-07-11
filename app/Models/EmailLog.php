<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = ['email', 'sent_at', 'opened_at'];

    public $timestamps = true;
}
