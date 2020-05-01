<?php


namespace App\testphpdi;


class MyDB
{
    private $db;

    public function __construct($connInfo)
    {
        // todo...
    }

    public function queryForRows($sql)
    {
        return ['user' => 231, 'userName' => '32344523'];
    }
}