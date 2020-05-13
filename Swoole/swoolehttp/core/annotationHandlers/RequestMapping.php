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
        $routerCollects->addRouter($requestMethod, $path, function ($params, $extParams) use ($method, $instance) {
//            return $method->invoke($instance); // 执行反射方法
            $inputParams = [];
            $refParams = $method->getParameters();// 反射参数类型
            foreach ($refParams as $refParam) {
                if (isset($params[$refParam->getName()])) {
                    $inputParams[] = $params[$refParam->getName()];
                } else {
                    // $extParam 都是实例对象，譬如 request response
                    foreach ($extParams as $extParam) {
                        if ($refParam->getClass() && $refParam->getClass()->isInstance($extParam)) { // 判断类型
                            $inputParams[] = $extParam;
                            goto next;
                        }
                    }
                    $inputParams[] = false;
                }
                next:
            }

            return $method->invokeArgs($instance, $inputParams);
        });
        return $instance;
    }

];