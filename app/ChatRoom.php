<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $fillable = ['id','userId','invited', 'roomName'];

    public function user(){
        return $this->belongsTo(User::class, 'userId');
    }

    protected $casts = [
        'invited' => 'array',
    ];
}
