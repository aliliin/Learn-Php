<?php
// php-di composer 包 和testphpdi 文件目录关联
require_once __DIR__ . '/vendor/autoload.php';
use App\testphpdi\MyUser;
// 使用注解
$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->useAnnotations(true);
$container = $containerBuilder->build();
$myUser = $container->get(MyUser::class);
var_dump($myUser);
var_dump($myUser->getAll(2));
die;
$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/app/testphpdi/Beans.php');
$container = $containerBuilder->build();
$myUser = $container->get(\App\testphpdi\MyUser::class);
var_dump($myUser->getAll(2));
echo \App\testphpdi\MyDB::class;
die;

$container->set('MyDB', function () {
    return new \App\testphpdi\MyDB('xxxxx');
});
$container->set('User', function (\DI\Container $c) {
    return new \App\testphpdi\MyUser($c->get('myDB'));
});
$myUser = $container->get('MyUser');
var_dump($myUser->getAll('33'));


