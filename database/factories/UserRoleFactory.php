<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserRole;
use Faker\Generator as Faker;

$factory->define(UserRole::class, function (Faker $faker) {
    return [
        
        'roleName'=>'admin',
        'permission'=>'[{"schoolName":{"resourceName":"Admin","read":true},"data":[{"resourceName":"使用者","read":true,"write":true,"update":true,"delete":true,"name":"adminuser"},{"resourceName":"角色","read":true,"write":true,"update":true,"delete":true,"name":"role"},{"resourceName":"分配角色","read":true,"write":true,"update":true,"delete":true,"name":"assignRole"},{"resourceName":"学校","read":true,"write":true,"update":true,"delete":true,"name":"School"},{"resourceName":"年级","read":true,"write":true,"update":true,"delete":true,"name":"Grade"},{"resourceName":"班级","read":true,"write":true,"update":true,"delete":true,"name":"Lesson"},{"resourceName":"stream","read":true,"write":true,"update":true,"delete":true,"name":"stream"}]}]'
    ];
});
