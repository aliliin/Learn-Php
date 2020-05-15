<?php


namespace App\Pool;


use Swoole\Runtime;

class PDOPool extends DBPool
{
    public function __construct($min = 5, $max = 10)
    {
        parent::__construct($min, $max);
        Runtime::enableCoroutine(true);
    }

    protected function newDB()
    {
        $dsn = "mysql:host=127.0.0.1;dbname=mysql_php";
        $pdo = new \PDO($dsn, 'root', '');
        return $pdo;
    }
}