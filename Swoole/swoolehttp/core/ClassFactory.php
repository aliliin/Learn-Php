<?php


namespace App\core;


use App\annotations\Bean;
use App\test\MyRedis;
use Doctrine\Common\Annotations\AnnotationReader;
use PhpParser\Node\Stmt\TraitUseAdaptation\Alias;
use function DI\string;

class ClassFactory
{
    private static $beans = [];

    public static function ScanBeans(string $paths, string $namespace)
    {
        $phpfiles = glob($paths . '/*.php');
        foreach ($phpfiles as $php) {
            require($php);
        }
        $classes = get_declared_classes();

        $reader = new AnnotationReader();
        foreach ($classes as $class) {
            if (strstr($class, $namespace)) {
                $refClass = new \ReflectionClass($class);
                $myAnnotation = $reader->getClassAnnotations($refClass);
                foreach ($myAnnotation as $annotation) {
                    if ($annotation instanceof Bean) {
                        $className = $refClass->getName();
                        self::$beans[$className] = self::loadClass($className, $refClass->newInstance());
                    }
                }
            }
        }

    }

    public static function getBeans(string $beanName)
    {
        return isset(self::$beans[$beanName]) ? self::$beans[$beanName] : null;
    }

    public static function loadClass($className, $object = false)
    {
        $refClass = new \ReflectionClass($className);
        $properties = $refClass->getProperties();

        $reader = new AnnotationReader();
        foreach ($properties as $property) {
            $myAnnotation = $reader->getPropertyAnnotations($property);
            foreach ($myAnnotation as $annotation) {
                $getValue = $annotation->do(); // 假设 do 返回我们的业务数据
                $retObj = $object ? $object : $refClass->newInstance();
                $property->setValue($retObj, $getValue);
                return $retObj;
            }
        }

        return $object ? $object : $refClass->newInstance();
    }

}