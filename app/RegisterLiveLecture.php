<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegisterLiveLecture extends Model
{
    protected $fillable = [
        'live_lecture_id',
        'userId',
    ];

    public function livelecture(){
        return $this -> belongsTo('App\LiveLecture');
    }
}
