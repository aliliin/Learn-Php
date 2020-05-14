<?php

namespace Core;

use DI\ContainerBuilder;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

class BeanFactory
{
    /**
     * 配置文件
     * @var array
     */
    private static $env = [];

    /**
     * Ioc 容器
     * @var
     */
    private static $containers;

    private static $annotationHandlers = [];

    public static function init()
    {
        /**
         * 初始化获取配置文件
         */
        self::$env = parse_ini_file(ROOT_PATH . "/env");

        /**
         *  初始化容器 builder
         */
        $builder = new ContainerBuilder();

        /**
         * 启用注解， 主要用它的 Inject 注解
         */
        $builder->useAnnotations(true);

        /**
         * 容器初始化
         */
        self::$containers = $builder->build();

        /**
         * 读取注解
         */
        $handlers = glob(ROOT_PATH . "/core/annotationhandlers/*.php");
        foreach ($handlers as $handler) {
            self::$annotationHandlers = array_merge(self::$annotationHandlers, require_once($handler));
        }

        /**
         * 注解第三方库的自动加载
         */
        $loader = require __DIR__ . '/../vendor/autoload.php';
        AnnotationRegistry::registerLoader([$loader, 'loadClass']);

        /**
         * 扫描
         */
        $scans = [
            ROOT_PATH . "/core/init" => "Core\\", // 先扫描框架内部必须要的文件夹
            self::getEnv('scan_dir', ROOT_PATH . '/app') => self::getEnv('scan_root_namespace', 'App\\'),
        ];

        foreach ($scans as $scanDir => $scanRootNamespace) {
            self::ScanBeans($scanDir, $scanRootNamespace);
        }
    }


    private static function getEnv(string $key, string $default = "")
    {
        if (isset(self::$env[$key])) return self::$env[$key];
        return $default;
    }

    /**
     * 处理app目录下的文件
     * @param string $dir app/
     * @return array
     */
    private static function getAllBeanFiles(string $dir)
    {
        $files = glob($dir . '/*');

        $result = [];
        foreach ($files as $file) {
            if (is_dir($file)) {// 如果是文件夹，就递归
                // 递归合并，防止数组变成了嵌套的多维数组
                $result = array_merge($result, self::getAllBeanFiles($file));
            } elseif (pathinfo($file)['extension'] == "php") {
                $result[] = $file;
            }
        }
        return $result;
    }

    /**
     * 扫描框架文件 处理注解
     */
    private static function ScanBeans(string $sCanDir, string $scanRootNamespace)
    {
        // 递归支持多级目录文件扫描
        $allFiles = self::getAllBeanFiles($sCanDir);
        foreach ($allFiles as $file) {
            require_once $file;
        }

        $reader = new AnnotationReader();

        foreach (get_declared_classes() as $getDeclaredClass) {
            if (strstr($getDeclaredClass, $scanRootNamespace)) {
                // 目标类的反射对象
                $refClass = new \ReflectionClass($getDeclaredClass);
                // 获取所有类注解
                $myAnnotation = $reader->getClassAnnotations($refClass);

                // 处理类注解
                foreach ($myAnnotation as $annotation) {
                    // 读取注解的类名
                    $handle = self::$annotationHandlers[get_class($annotation)];
                    $instance = self::$containers->get($refClass->getName());
                    // 处理属性注解
                    self::handlePropAnnotation($instance, $refClass, $reader);
                    // 处理方法注解
                    self::handleMethodAnnotation($instance, $refClass, $reader);
                    //  处理类注解
                    $handle($instance, self::$containers, $annotation); // 执行处理

                }
            }
        }
    }

    /**
     * 处理属性注解
     * @param $instance
     * @param \ReflectionClass $refClass
     * @param AnnotationReader $reader
     */
    private static function handlePropAnnotation(&$instance, \ReflectionClass $refClass, AnnotationReader $reader)
    {
        $props = $refClass->getProperties();// 取出反射对象的所有属性
        foreach ($props as $prop) {
            $propAnnos = $reader->getPropertyAnnotations($prop);
            foreach ($propAnnos as $propAnno) {
                $handle = self::$annotationHandlers[get_class($propAnno)];
                $instance = $handle($prop, $instance, $propAnno);// 处理属性注解
            }
        }
    }

    /**
     * 处理方法注解
     * @param $instance
     * @param \ReflectionClass $refClass
     * @param AnnotationReader $reader
     */
    private static function handleMethodAnnotation(&$instance, \ReflectionClass $refClass, AnnotationReader $reader)
    {
        $methods = $refClass->getMethods();// 取出反射对象的所有方法
        foreach ($methods as $method) {
            $methodAnnos = $reader->getMethodAnnotations($method);
            foreach ($methodAnnos as $methodAnno) {
                $handle = self::$annotationHandlers[get_class($methodAnno)];
                $instance = $handle($method, $instance, $methodAnno);// 处理方法注解
            }
        }
    }

    public static function getBean($name)
    {
        return self::$containers->get($name);
    }
}