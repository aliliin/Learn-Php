<?php

interface InterFaceCache
{
    public function get();
    public function set();
}


class Mysql implements InterFaceCache
{
    public function get()
    {
    }
    public function set()
    {
    }
}

class Redis implements InterFaceCache
{
    public function get()
    {
    }
    public function set()
    {
    }
}

class Cache
{
    public static function instance(string $driver)
    {
        switch (strtolower($driver)) {
           case 'mysql':
               return new Mysql();
               break;
           case 'redis':
                return new Redis();
                break;
           default:
               # code...
               break;
       }
    }
}

var_dump(Cache::instance('redis'));
