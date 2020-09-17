<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Member;
use Faker\Generator as Faker;

$factory->define(Member::class, function (Faker $faker) {
    return [
        //
        'schoolId'=>1,
        'gradeId'=>1,
        'lessonId'=>1,
        'userId'=>1,
        'UserRoleId'=>3
    ];
});
