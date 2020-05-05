<?php

class User
{
    protected $name;
    protected static $className = "静态属性";

    public function say()
    {
        return $this->getName();
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }
    public static function getClassName()
    {
        return self::$className;
    }
}

$obj = new User();
$obj->setName('Aliliin');
echo $obj->say();
echo "\n";
echo User::getClassName();
