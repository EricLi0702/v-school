<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Permission;
use Faker\Generator as Faker;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        //
        'userId'=>1,
        'roleId'=>1,
        'permission'=>'[{"schoolName":{"resourceName":"系统设置","read":true},"data":[{"resourceName":"名单","read":true,"write":true,"update":true,"delete":true,"name":"adminuser"},{"resourceName":"角色","read":true,"write":true,"update":true,"delete":true,"name":"role"},{"resourceName":"分配角色","read":true,"write":true,"update":true,"delete":true,"name":"assignRole"},{"resourceName":"学校","read":true,"write":true,"update":true,"delete":true,"name":"School"},{"resourceName":"年级","read":true,"write":true,"update":true,"delete":true,"name":"Grade"},{"resourceName":"班级","read":true,"write":true,"update":true,"delete":true,"name":"Lesson"},{"resourceName":"stream","read":true,"write":true,"update":true,"delete":true,"name":"stream"},{"resourceName":"imei管理","read":true,"write":true,"update":true,"delete":true,"name":"imeiManage"}]}]'
    ];
});
