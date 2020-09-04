<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AllPost;
use Faker\Generator as Faker;

$factory->define(AllPost::class, function (Faker $faker) {
    return [
        //
        'title'=>$faker->name,
        'content'=>$faker->text,
        'img'=>'/uploads/1599176161.jpeg',
        'from'=>1,
        'readCnt'=>0,
        'likeCnt'=>0,
        'isliked'=>0

    ];
});
