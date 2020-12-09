<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    //
    protected $guarded = [];

    public function users(){
        return $this->belongsTo(User::class, 'userId');
    }

}
