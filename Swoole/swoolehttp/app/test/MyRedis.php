<?php


namespace App\test;

use App\annotations\Bean;
use App\annotations\Value;

// 写上 bean 注解就可以使用关联到
/**
 * @Bean()
 * Class MyRedis
 * @package App\test
 */
class MyRedis
{
    /**
     * @Value(name="url");
     */
    public $conn_url;

}