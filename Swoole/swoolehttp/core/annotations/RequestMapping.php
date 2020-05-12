<?php


namespace Core\annotations;


use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation()
 * @Target({"METHOD"})
 * Class RequestMapping 方法注解
 * @package Core\annotations
 */
class RequestMapping
{
    /**
     * 路径 如/api/test
     */
    public $value = "";

    /**
     * GET 、POST  等
     */
    public $method = [];

}