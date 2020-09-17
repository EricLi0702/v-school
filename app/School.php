<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    //
    protected $guarded = [];

    public function grades(){
        return $this->hasMany(Grade::class,'schoolId');
    }

    public function lessons(){
        return $this->hasMany(Lesson::class,'schoolId');
    }
}
