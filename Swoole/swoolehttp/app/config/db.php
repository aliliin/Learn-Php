<?php

return [
    "default" => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'mysql_php',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ],
    "slave" => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'laravel6',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ]
];