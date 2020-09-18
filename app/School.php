<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    //
    protected $guarded = [];

    public function grades(){
        return $this->hasMany('App\Grade','schoolId');
    }

    public function lessons(){
        return $this->hasMany('App\Lesson','schoolId');
    }
}
