<?php
require_once __DIR__ . "/vendor/autoload.php";

use Swoole\Http\Response;
use Swoole\Http\Request;


$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/test', function () {
        return 'my test';
    });
});

$http = new Swoole\Http\Server('0.0.0.0', 8090);
$http->on('request', function (Request $request, Response $response) use ($dispatcher) {
    $myRequest = \App\core\Request::init($request);
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
            $response->end($handler());

//            $vars = $routeInfo[2];
            // ... call $handler with $vars
            break;
    }
});

$http->start();