<?php
require_once __DIR__ . "/vendor/autoload.php";

require_once __DIR__ . "/app/config/define.php";

\Core\BeanFactory::init();
$user = \Core\BeanFactory::getBean("Aliliin");
var_dump($user);
$user = \Core\BeanFactory::getBean(\App\controller\UserController::class);
//$user = \Core\BeanFactory::getBean("UserController");
var_dump($user);


$routers = \Core\BeanFactory::getBean("RouterCollects");
//$user = \Core\BeanFactory::getBean("UserController");
var_dump($routers->routes);
