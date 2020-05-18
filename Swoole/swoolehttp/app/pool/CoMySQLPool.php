<?php


namespace App\pool;


use Swoole\Coroutine\MySQL;

class CoMySQLPool extends DBPool
{
    public function __construct($min = 5, $max = 10)
    {
        parent::__construct($min, $max);
    }

    protected function newDB()
    {
        $mysql = new MySQL();
        $mysql->connect(
            [
                'host' => '127.0.0.1',
                'user' => 'root',
                'password' => '',
                'database' => 'mysql_php'
            ]
        );
        return $mysql;
    }

}