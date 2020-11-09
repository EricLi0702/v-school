<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    //
    protected $guarded = [];

    public function school(){
        return $this->belongsTo(School::class, 'schoolId');
    }
    public function grade(){
        return $this->belongsTo(Grade::class, 'gradeId');
    }
    public function lesson(){
        return $this->belongsTo(Lesson::class, 'lessonId');
    }
    public function user(){
        return $this->belongsTo(User::class, 'userId');
    }
}
