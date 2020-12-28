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

    public function school(){
        return $this->belongsTo(School::class,'schoolId');
    }

    public function grade(){
        return $this->belongsTo(Grade::class,'gradeId');
    }

    public function lesson(){
        return $this->belongsTo(Lesson::class,'lessonId');
    }
}
