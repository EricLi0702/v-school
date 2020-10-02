<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    //
    protected $guarded = [];
    
    public function bulletin(){
        return $this->hasMany(BulletinBoard::class,'contentType');
    }
}
