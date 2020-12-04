<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserRole;
use Faker\Generator as Faker;

$factory->define(UserRole::class, function (Faker $faker) {
    return [
        
        'roleName'=>'admin',
        'permission'=>'[{"schoolName":{"resourceName":"Admin","read":true},"data":[{"resourceName":"使用者","read":true,"write":false,"update":false,"delete":false,"name":"adminuser"},{"resourceName":"角色","read":true,"write":false,"update":false,"delete":false,"name":"role"},{"resourceName":"分配角色","read":true,"write":false,"update":false,"delete":false,"name":"assignRole"},{"resourceName":"学校","read":true,"write":false,"update":false,"delete":false,"name":"School"},{"resourceName":"年级","read":true,"write":false,"update":false,"delete":false,"name":"Grade"},{"resourceName":"班级","read":true,"write":false,"update":false,"delete":false,"name":"Lesson"},{"resourceName":"stream","read":true,"write":false,"update":false,"delete":false,"name":"stream"}]}]'
    ];
});
