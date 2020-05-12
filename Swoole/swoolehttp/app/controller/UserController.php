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
     * @RequestMapping(value="/index")
     */
    public function index()
    {
        return "Aliliin";
    }

    /**
     * @RequestMapping(value="/user")
     */
    public function user()
    {
        return "user";
    }
}