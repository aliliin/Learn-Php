<?php


namespace Core\lib;

use Core\BeanFactory;
use Core\init\PHPRedisPool;


/**
 * Class RedisHelper
 * @method static string get (string $key)
 * @method static bool set (string $key, string $value)
 * @method static bool setex(string $key, int $ttl, string $value)
 * @method static array hgetall(string $key)
 * @method static bool hmset(string $key, array $value)
 * @method static int hIncrBy($key, $hashKey, $value)
 * @method static int zAdd($key, $options, $score1, $value1)
 * @method static mixed eval($script, $args = array(), $numKeys = 0)
 * @method static float|bool zScore(string $key, string|mixed $member)
 */
class RedisHelper
{
    public static function __callStatic($name, $arguments)
    {
//        new \Redis()
        $pool = BeanFactory::getBean(PHPRedisPool::class);
        $redisObj = $pool->getConnection();

        try {
            if (!$redisObj) return false;
            $redis = $redisObj->redis;
            return $redis->$name(...$arguments);
        } catch (\Exception $exceptione) {
            var_dump($exceptione->getMessage());
            return false;
        } finally {
            if ($redisObj) {
                $pool->close($redisObj);
            }
        }
    }
}