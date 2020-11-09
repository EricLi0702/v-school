<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Attendance;
use Faker\Generator as Faker;

$factory->define(Attendance::class, function (Faker $faker) {
    return [
        //
        'scheduleTimes'=>40,
        'workedTimes'=>$faker->numberBetween(25,40),
        'lateCnt'=>$faker->numberBetween(0,1),
        'earlyLeaveCnt'=>$faker->numberBetween(0,1),
        'absentCnt'=>$faker->numberBetween(0,1),
        'sickCnt'=>$faker->numberBetween(0,1),
        'tripCnt'=>$faker->numberBetween(0,1),
        'workday'=>$faker->dateTimeThisMonth(),
        'userId' => App\User::all()->random()->id,
        // 'type' => $faker->randomElement(['seller', 'buyer']),
    ];
});
