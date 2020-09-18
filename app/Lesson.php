<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    //
    protected $guarded = [];
    
    public function grades(){
        return $this->belongsTo(Grade::class,'gradeId');
    }
    public function schools(){
        return $this->belongsTo(School::class,'schoolId');
    }
}
