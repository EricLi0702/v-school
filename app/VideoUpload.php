<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class VideoUpload extends Model
{
    protected $fillable = [
        'id',
        'userId', 
        'videoFile',
        'description',
        'grade',
        'subject',
        'title',
        'view_cnt',
        'like_cnt',
    ];
    // protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class,'userId');
    }

    protected $casts = [
        'view_cnt' => 'array',
        'like_cnt' => 'array'
    ];
    
}
