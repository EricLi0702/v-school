<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnswerBulletin extends Model
{
    //
    protected $guarded = [];

    public function bulletin(){
        return $this->belongsTo(BulletinBoard::class,'bulletinId');
    }
}
