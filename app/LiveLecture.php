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
        'cover_image',
        'lecture_time',
    ];
}
