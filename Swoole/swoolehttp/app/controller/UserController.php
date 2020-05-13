<?php

namespace App\controller;

use Core\annotations\Bean;
use Core\annotations\RequestMapping;
use Core\annotations\Value;

/**
 * @Bean(name="Aliliin")
 */
class UserController
{
    /**
     * @Value(name="version")
     */
    public $version;

    /**
     * @RequestMapping(value="/index/{uid:\d+}")
     */
    public function index(string $name, int $uid)
    {
        return "Aliliin" . $uid;
    }

    /**
     * @RequestMapping(value="/user")
     */
    public function user()
    {
        return "user";
    }
}