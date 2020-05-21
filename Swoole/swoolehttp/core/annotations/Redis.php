<?php


namespace Core\annotations;


use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Redis
{
    public $source = "default";
    public $key = "";
    public $prefix = '';
    public $type = 'string';
    public int  $timeout = 0; // 过期时间
    public $incr = ''; // 暂时只支持 hash 类型
}