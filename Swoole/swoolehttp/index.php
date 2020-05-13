<?php
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/app/config/define.php";


use Swoole\Http\Response;
use Swoole\Http\Request;

\Core\BeanFactory::init();

$dispatcher = \Core\BeanFactory::getBean("RouterCollects")->getDispatcher();


$http = new Swoole\Http\Server('0.0.0.0', 9501);
$http->on('request', function (Request $request, Response $response) use ($dispatcher) {
    $myRequest = \Core\Http\Request::init($request);
    $myResponse = \Core\http\Response::init($response);
    $routeInfo = $dispatcher->dispatch($myRequest->getMethod(), $myRequest->getUri());
    // [1,$dispatcher,$var] 有三个值
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            $response->status(404);
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $allowedMethods = $routeInfo[1];
            $response->status(405);
            break;
        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];// ... call $handler with $vars
            $extVars = [$myRequest,$myResponse];
            // 设置响应 body 部分
            $myResponse->setBody($handler($vars, $extVars));
            $myResponse->end();
            break;
    }
});

$http->start();
