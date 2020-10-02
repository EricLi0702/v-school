<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'userId');
    }
    public function bulletin(){
        return $this->belongsTo(BulletinBoard::class,'bulletinId');
    }
}
