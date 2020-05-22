<?php


namespace Core\annotations;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"METHOD"})
 * Class Lock
 * @package Core\annotations
 */
class Lock
{
    public string $prefix = '';
    public string $key = '';
    public int $retry = 3; // 重试次数
    public int $expire = 10; // 过期时间
}