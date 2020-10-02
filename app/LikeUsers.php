<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LikeUsers extends Model
{
    //
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(Users::class,'userId');
    }
    public function bulletin(){
        return $this->belongsTo(BulletinBoard::class,'bulletinId');
    }
}
