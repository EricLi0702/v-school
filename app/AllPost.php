<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllPost extends Model
{
    //
    // protected $guarded = [];
    protected $fillable=[
        'title','content','img','from','readCnt','likeCnt','isliked'
    ];

    public function from(){
        return $this->belongsTo(User::class, 'roleId');
    }
}
