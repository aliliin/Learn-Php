<?php
require_once __DIR__ . '/vendor/autoload.php';

//require_once __DIR__.'/app/config/define.php';


use Swoole\Coroutine;
use App\Pool\PDOPool;

go(function () {
    $pool = new PDOPool();
    $pool->initPool();

    for ($i = 0; $i < 5; $i++) {
        go(function () use ($pool, $i) {
            $conn = $pool->getConnection();

            defer(function () use ($pool, $conn) {
                $pool->close($conn);
            });
            $state = $conn->query("select sleep(10)");
            $state->setFetchMode(PDO::FETCH_ASSOC);
            $rows = $state->fetchAll();

        });
    }
    for ($i = 0; $i < 3; $i++) {
        go(function () use ($pool, $i) {
            $conn = $pool->getConnection();
            defer(function () use ($pool, $conn) {
                $pool->close($conn);
            });
            $state = $conn->query("select $i");
            $state->setFetchMode(PDO::FETCH_ASSOC);
            $rows = $state->fetchAll();
            var_dump($rows);
        });
    }
    for ($i = 0; $i < 4; $i++) {
        go(function () use ($pool, $i) {
            $conn = $pool->getConnection();
            defer(function () use ($pool, $conn) {
                $pool->close($conn);
            });
            $state = $conn->query("select $i");
            $state->setFetchMode(PDO::FETCH_ASSOC);
            $rows = $state->fetchAll();
            var_dump($rows);
        });
    }
    while (true) {
        Coroutine::sleep(1);
    }
});