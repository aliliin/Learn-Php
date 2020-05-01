<?php


namespace App\testphpdi;

use DI\Container;

return [
    MyDB::class => function () {
        return new MyDB('数据库链接');
    },
    MyUser::class => function (Container $container) {
        return new MyUser($container->get(MyDB::class));
    }
];

