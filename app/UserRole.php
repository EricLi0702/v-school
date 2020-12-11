<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    //
    protected $fillable = ['roleName'];

    public function user(){
        return $this->hasMany(User::class, 'userId');
    }
}
