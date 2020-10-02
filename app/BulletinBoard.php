<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BulletinBoard extends Model
{
    //
    // protected $guarded = [];
    protected $fillable = [
        'userId','addData','answerUserList','contentType'
    ];
    public function user(){
        return $this->belongsTo('App\User','userId');
    }

    public function content(){
        return $this->belongsTo('App\Content','contentType');
    }
    public function answers(){
        return $this->hasMany('App\AnswerBulletin','bulletinId');
    }
    public function comments(){
        return $this->hasMany(Comment::class,'bulletinId');
    }
    public function likes(){
        return $this->hasMany(LikeUsers::class,'bulletinId');
    }
}
