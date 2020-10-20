<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LiveLecture extends Model
{
    protected $fillable = [
        'id',
        'userId',
        'teacher_name',
        'lecture_title',
        'lecture_description',
        'lecture_time',
        'grade',
        'subject',
        'status',
        'registered_members',
    ];

    public function registerlivelecture(){
        return $this -> hasMany('App\RegisterLiveLecture');
    }
}
