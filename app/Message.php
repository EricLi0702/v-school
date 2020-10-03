<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Message extends Model
{
    protected $fillable = [
        'from',
        'to',
        'text', 
        'image',
        'video',
        'voice',
        'map',
        'file',
    ];

    public function from(){
        return $this->belongsTo(User::class, 'from');
    }
    public function to(){
        return $this->belongsTo(User::class,'to');
    }
}
