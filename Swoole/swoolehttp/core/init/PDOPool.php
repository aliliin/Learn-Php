<?php


namespace Core\init;


use Core\lib\DBPool;
use Core\annotations\Bean;

/**
 * @Bean()
 * Class PDOPool
 */
class PDOPool extends DBPool
{
    public function __construct(int $min = 5, int $max = 10, int $linkTimeFree = 10)
    {
        global $GLOBALS_CONFIGS;
        $poolConfig = $GLOBALS_CONFIGS['DBPool']['default'];
        parent::__construct($poolConfig['min'], $poolConfig['max'], $poolConfig['linkTimeFree']);
    }

    protected function newDB()
    {
        global $GLOBALS_CONFIGS;
        $default = $GLOBALS_CONFIGS['db']['default'];
        $dsn = "";
        {
            $driver = $default['driver'];
            $host = $default['host'];
            $database = $default['database'];
            $username = $default['username'];
            $password = $default['password'];
            $dsn = "$driver:host=$host;dbname=$database";
        }

        $pdo = new \PDO($dsn, $username, $password);
        return $pdo;
    }
}