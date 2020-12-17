<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImeiManage extends Model
{
    //
    protected $guarded = [];
    protected $casts = [
        'imeiList'=>'array'
    ];
}
