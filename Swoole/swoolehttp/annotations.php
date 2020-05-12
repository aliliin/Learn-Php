<?php
// doctrine/annotations composer 包 和 app/annotations 文件目录关联
require_once __DIR__ . '/vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use App\test\MyRedis;
use App\annotations\Value;
use App\core\ClassFactory;


// 注册注解的命名空间
AnnotationRegistry::registerLoader('class_exists');

ClassFactory::ScanBeans(__DIR__ . '/app/test', 'App\\test');
$class = ClassFactory::getBeans(MyRedis::class);
var_dump($class);

// $myredis = ClassFactory::loadClass(MyRedis::class);
// var_dump($myredis  );


$reflectionClass = new ReflectionClass(MyRedis::class);
$property = $reflectionClass->getProperty('conn_url');

$reader = new AnnotationReader();
$myAnnotation = $reader->getPropertyAnnotation($property, Value::class);

echo $myAnnotation->name; // result: "url"





