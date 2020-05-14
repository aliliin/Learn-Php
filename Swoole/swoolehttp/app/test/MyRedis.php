<?php


namespace App\test;

use Core\annotations\Bean;
use Core\annotations\RequestMapping;
use Core\annotations\Value;


// 写上 bean 注解就可以使用关联到

/**
 * @Bean()
 * Class MyRedis
 * @package
 */
class MyRedis
{
    /**
     * @Value(name="url");
     */
    public $conn_url;


    /**
     * @RequestMapping(value="/myredis")
     */
    public function index()
    {
        return "test redis controller ";
    }
}