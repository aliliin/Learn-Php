<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */
namespace App\Services;

use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Cache\Annotation\CachePut;

class CacheService
{
    /**
     * @Cacheable(prefix="test", value="#{TestClass.age}")
     */
    public function getTest(TestClass $testClass)
    {
        return $this->get($testClass->name);
    }

    /**
     * @Cacheable(prefix="cache")
     */
    public function getCache(string $name = 'hyperf')
    {
        return $this->get($name);
    }

    /**
     * @CachePut(prefix="cache")
     * @return string
     */
    public function putCache(string $name = 'Aliliin')
    {
        // 先查询缓存，再重新此缓存
        return $this->get($name);
    }

    public function get(string $name = 'hyperf')
    {
        sleep(1);
        return 'Hello ' . $name . '-' . uniqid();
    }
}
