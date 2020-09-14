<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoUpload extends Model
{
    protected $fillable = [
        'id',
        'userId', 
        'videoFile',
        'description',
        'grade',
        'subject',
    ];
    // protected $guarded = [];

    
}
