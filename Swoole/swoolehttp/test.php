<?php

// 依赖注入 IoC
class myDB
{
    private $db;

    public function __construct($connInfo)
    {
        // todo...
    }

    public function queryForRows($sql)
    {
        return ['user' => 231, 'userName' => '322323'];
    }

}

class User
{
    private $mydb;

    public function __construct(myDB $DB)
    {
        $this->mydb = $DB;
    }

    public function getAll(): array // 业务代码
    {
        return $this->mydb->queryForRows('select * form users');
    }
}

class  ClassFactory
{
    private static $container = []; // 容器，

    public static function set(string $name, callable $func)
    {
        self::$container[$name] = $func;
    }

    public static function get(string $name)
    {
        if (isset(self::$container[$name])) return (self::$container[$name])();

        return null;
    }

}

ClassFactory::set('myDB', function () {
    return new myDB('xxxx000');
});
ClassFactory::set('User', function () {
    return new User(ClassFactory::get('myDB'));
});
$user = ClassFactory::get('User');
var_dump($user->getAll('22'));
die;

// 控制顺序会发生变化
$db = new myDB('ddsdse');
$user = new User($db);
var_dump($user->getAll('2'));