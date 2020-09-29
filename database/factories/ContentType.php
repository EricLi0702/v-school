<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Content;
use Faker\Generator as Faker;

$factory->define(Content::class, function (Faker $faker) {
    return [
        //
        'contentName'=>'问卷',
        'imgUrl'=>'/img/icon/公告 拷贝.png'
    ];
});
