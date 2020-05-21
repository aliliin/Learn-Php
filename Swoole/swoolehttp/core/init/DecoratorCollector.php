<?php


namespace Core\init;

use Core\annotations\Bean;

/**
 * 装饰器收集类
 * Class DecoratorCollector
 * @Bean()
 */
class DecoratorCollector
{
    public array $dSet = [];

    public function exec(\ReflectionMethod $method, $instance, $inputParams)
    {
        $key = get_class($instance) . "::" . $method->getName();
        if (isset($this->dSet[$key])) {
            $function = $this->dSet[$key];
            // 装饰器执行
            return $function($method->getClosure($instance))($inputParams);
        }
        // 原样执行
        return $method->invokeArgs($instance, $inputParams);
    }
}