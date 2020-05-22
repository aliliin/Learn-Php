<?php


namespace Core\annotationHandlers;


use Core\annotations\Lock;
use Core\BeanFactory;
use Core\init\DecoratorCollector;
use Core\lib\RedisHelper;

function getLock(Lock $self, $params)
{
    $script = <<<Language
            local key = KEYS[1]
            local expire = ARGV[1]
            if redis.call('setnx',key,1) == 1 then 
                        return redis.call('expire',key,expire)
            end 
            return 0
        Language;
    return RedisHelper::eval($script, [$self->prefix . getKey($self->key, $params), $self->expire], 1);
}

function delLock(Lock $self, $params)
{
    $script = <<<Language
            local key = KEYS[1]
            return redis.call('del',key,1)
        Language;
    return RedisHelper::eval($script, [$self->prefix . getKey($self->key, $params)], 1);
}

function lock(Lock $self, $params)
{
    $retry = $self->retry;
    while ($retry-- > 0) {
        if (getLock($self, $params)) {
            return true;
        }
        usleep(1000 * 100 * 1); // 休眠 100 毫秒
    }
    return false;
}

function running(Lock $self, $params, $function)
{
    try {
        if (lock($self, $params)) {
            $ret = call_user_func($function, ...$params);
            delLock($self, $params);
            return $ret;
        }
        return false;
    } catch (\Exception $exception) {
        delLock($self, $params);
        return false;
    }
}

return [
    Lock::class => function (\ReflectionMethod $method, $instance, $self) {
        $decoratorCollector = BeanFactory::getBean(DecoratorCollector::class);
        $key = get_class($instance) . "::" . $method->getName();
        // 收集装饰器 放入 装饰器收集类
        $decoratorCollector->dSet[$key] = function ($function) use ($self) {
            return function ($params) use ($function, $self) {
                if ($self->key != '') { // 处理缓存
                    $res = running($self, $params, $function);
                    if ($res === false) {
                        return ['locking'];
                    } else {
                        return $res;
                    }
                }
                return call_user_func($function, ...$params);
            };
        };
        return $instance;
    }
];