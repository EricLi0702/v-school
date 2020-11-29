<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ChatRoom;

class Contact extends Model
{
    protected $fillable = [
        'userId','contactUserId', 'roomId'
    ];
    public function user(){
        return $this->belongsTo(User::class,'contactUserId');
    }
    public function roomId(){
        return $this->belongsTo(ChatRoom::class,'roomId');
    }
}
