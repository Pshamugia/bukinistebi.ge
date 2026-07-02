<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OwnerNotification extends Model
{
    protected $fillable = [
        'recipient_email',
        'actor_id',
        'actor_name',
        'actor_email',
        'type',
        'title',
        'message',
        'url',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];
}
