<?php

error_reporting(E_ALL ^ E_NOTICE);
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/config/define.php';

// 装饰器模式
$showName = function ($name) {
    return $name;
};

function A($func)
{
    return function ($param) use ($func) {

        return $func('A' . $param);
    };
}

function B($func)
{
    return function ($param) use ($func) {

        return $func('B' . $param);
    };
}

echo A($showName)('Alibiing');
echo B($showName)('Alibiing');

class DecoratorDefinition
{
    public function showName($name)
    {
        return $name;
    }
}

$class = new \ReflectionClass("DecoratorDefinition");
$showName = $class->getMethod('showName');
echo $showName->invoke(new DecoratorDefinition(),'alias');
echo A($showName->getClosure(new DecoratorDefinition()))('Alibiing');

