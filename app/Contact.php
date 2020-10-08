<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'userId','contactUserId'
    ];
    public function user(){
        return $this->belongsTo(User::class,'contactUserId');
    }
}
