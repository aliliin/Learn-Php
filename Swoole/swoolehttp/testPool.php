<?php
require_once __DIR__ . '/vendor/autoload.php';

//require_once __DIR__.'/app/config/define.php';


use Swoole\Coroutine;

go(function () {
    $pool = new \App\Pool\PDOPool();
    $pool->initPool();

    while (true) {
        Coroutine::sleep(1);
    }

});