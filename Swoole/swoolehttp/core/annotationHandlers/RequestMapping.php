<?php


namespace Core\annotationHandlers;

use Core\annotations\RequestMapping;
use Core\BeanFactory;

/**
 * 路由收集器
 */
return [
    RequestMapping::class => function (\ReflectionMethod $method, $instance, $self) {

        $path = $self->value;// uri
        $requestMethod = count($self->method) > 0 ? $self->method : ['GET'];
        $routerCollects = BeanFactory::getBean("RouterCollects");
        // 收集路由
        $routerCollects->addRouter($requestMethod, $path, function () use ($method, $instance) {
            return $method->invoke($instance); // 执行反射方法
        });
        return $instance;
    }

];