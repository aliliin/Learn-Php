<?php


namespace Core\init;


use Core\lib\RedisPool;
use Core\annotations\Bean;

/**
 * @Bean()
 * Class PHPRedisPool
 */
class PHPRedisPool extends RedisPool
{
    public function __construct(int $min = 5, int $max = 10, int $linkTimeFree = 10)
    {
        global $GLOBALS_CONFIGS;
        $poolConfig = $GLOBALS_CONFIGS['RedisPool']['default'];
        parent::__construct($poolConfig['min'], $poolConfig['max'], $poolConfig['linkTimeFree']);
    }

    protected function newRedis()
    {
        global $GLOBALS_CONFIGS;
        $default = $GLOBALS_CONFIGS['Redis']['default'];
        $redis = new \Redis();
        $redis->connect($default['host'], $default['port']);
        if ($default['auth'] != '') {
            $redis->auth($default['auth']);
        }
        return $redis;
    }
}