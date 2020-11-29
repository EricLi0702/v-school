<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\ChatRoom;

class Message extends Model
{
    protected $fillable = [
        'from',
        'to',
        'text', 
        'image',
        'video',
        'voice',
        'map',
        'file',
        'roomId',
    ];

    public function from(){
        return $this->belongsTo(User::class, 'from');
    }
    public function to(){
        return $this->belongsTo(User::class,'to');
    }
    public function roomId(){
        return $this->belongsTo(ChatRoom::class,'roomId');
    }
}
