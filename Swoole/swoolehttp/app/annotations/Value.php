<?php


namespace App\annotations;


use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Value
{
    public $name;

    public function do()
    {
        $ini = parse_ini_file('./conf');
        if(isset($ini[$this->name])){
            return $ini[$this->name];
        }
        return  null;
    }
}