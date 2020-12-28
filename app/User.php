<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id';
     protected $fillable = [
        'name', 'phoneNumber', 'password', 'userAvatar', 'faceImg', 'isActived', 'roleId', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function role(){
        return $this->belongsTo(UserRole::class, 'roleId');
    }
    // public function permission(){
    //     return $this->hasOne(Permission::class, 'userId');
    // }
    public function member(){
        return $this->hasOne(Member::class,'userId');
    }
    public function imei(){
        return $this->hasOne(ImeiManage::class,'userId');
    }

}
