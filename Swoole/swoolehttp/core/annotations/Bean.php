<?php


namespace Core\annotations;


use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation()
 * @Target({"CLASS"})
 * Class Bean
 * @package App\annotations
 */
class Bean
{
    public $name = "";
}