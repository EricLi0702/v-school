<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    //
    protected $fillable = ['roleName','permission'];

    public function user(){
        return $this->hasMany(User::class, 'userId');
    }
}
