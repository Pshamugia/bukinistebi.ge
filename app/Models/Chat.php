<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['user_id', 'admin_id', 'status'];

    public function messages()
{
    return $this->hasMany(Message::class);
}

public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function admin()
{
    return $this->belongsTo(User::class, 'admin_id');
}
}
