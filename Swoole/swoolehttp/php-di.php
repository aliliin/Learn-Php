<?php
// php-di composer 包
require_once __DIR__ . '/vendor/autoload.php';

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


