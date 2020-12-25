<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserRole;
use Faker\Generator as Faker;

$factory->define(UserRole::class, function (Faker $faker) {
    return [
        
        'roleName'=>'admin',
        'permission'=>'[{"title":"\u6743\u9650\u7ba1\u7406","expand":true,"children":[{"title":"\u5b66\u6821\u7a7a\u95f4","expand":true,"children":[{"title":"\u95ee\u5377","nodeKey":2,"checked":true,"indeterminate":false},{"title":"\u6295\u7968","nodeKey":3,"checked":true,"indeterminate":false},{"title":"\u77ed\u4fe1","nodeKey":4,"checked":true,"indeterminate":false},{"title":"\u6821\u56ed\u52a8\u6001","nodeKey":5,"checked":true,"indeterminate":false},{"title":"\u516c\u544a","nodeKey":6,"checked":true,"indeterminate":false},{"title":"\u5e03\u544a\u680f","nodeKey":7,"checked":true,"indeterminate":false},{"title":"\u8003\u52e4","nodeKey":8,"checked":true,"indeterminate":false},{"title":"\u4f5c\u4e1a","nodeKey":9,"checked":true,"indeterminate":false},{"title":"\u4e60\u9898","nodeKey":10,"checked":true,"indeterminate":false},{"title":"\u5bb6\u8bbf","nodeKey":11,"checked":true,"indeterminate":false}],"nodeKey":1,"checked":true,"indeterminate":false},{"title":"\u73ed\u7ea7\u7a7a\u95f4","expand":true,"children":[{"title":"\u95ee\u5377","nodeKey":13,"checked":true,"indeterminate":false},{"title":"\u6295\u7968","nodeKey":14,"checked":true,"indeterminate":false},{"title":"\u4f5c\u4e1a","nodeKey":15,"checked":true,"indeterminate":false},{"title":"\u4e60\u9898","nodeKey":16,"checked":true,"indeterminate":false},{"title":"\u5bb6\u8bbf","nodeKey":17,"checked":true,"indeterminate":false},{"title":"\u901a\u77e5","nodeKey":18,"checked":true,"indeterminate":false},{"title":"\u8bc4\u4ef7","nodeKey":19,"checked":true,"indeterminate":false},{"title":"\u8868\u5f70","nodeKey":20,"checked":true,"indeterminate":false},{"title":"\u8bfe\u8868","nodeKey":21,"checked":true,"indeterminate":false},{"title":"\u76f8\u518c","nodeKey":22,"checked":true,"indeterminate":false},{"title":"\u6587\u4ef6","nodeKey":23,"checked":true,"indeterminate":false}],"nodeKey":12,"checked":true,"indeterminate":false}],"nodeKey":0,"checked":true,"indeterminate":false}]'
    ];
});
