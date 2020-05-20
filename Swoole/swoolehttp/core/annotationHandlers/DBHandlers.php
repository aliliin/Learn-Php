<?php


namespace Core\annotationHandlers;

use Core\annotations\DB;
use Core\BeanFactory;
use Core\init\MyDB;

return [
    // 属性注解
    DB::class => function (\ReflectionProperty $prop, $instance, $self) {
        $myDbBean = null;
        // 判断主从
        if ($self->source != 'default') {
            $beanName = MyDB::class . '_' . $self->source;
            $myDbBean = BeanFactory::getBean($beanName);
            if (!$myDbBean) {
                // 复制一个对象
                $myDbBean = clone BeanFactory::getBean(MyDB::class);
                $myDbBean->setDbSource($self->source);
                BeanFactory::setBean($beanName, $myDbBean);
            } else {
                $myDbBean = clone $myDbBean;
            }
        } else {
            $myDbBean = clone BeanFactory::getBean(MyDB::class);
        }

        // 处理私有属性
        $prop->setAccessible(true);
        $prop->setValue($instance, $myDbBean);
        return $instance;
    }
];