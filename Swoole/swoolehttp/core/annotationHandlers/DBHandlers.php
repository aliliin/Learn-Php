<?php


namespace Core\annotationHandlers;

use Core\annotations\DB;
use Core\BeanFactory;
use Core\init\MyDB;

return [
    // 属性注解
    DB::class => function (\ReflectionProperty $prop, $instance, $self) {
        $myDbBean = BeanFactory::getBean(MyDB::class);
        // 处理私有属性
        $prop->setAccessible(true);
        $prop->setValue($instance, $myDbBean);
        return $instance;
    }
];