<?php

declare(strict_types=1);

namespace App\Middleware;

use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CheckUserMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    protected $request;
    public function __construct(ContainerInterface $container,RequestInterface $request,\Hyperf\HttpServer\Contract\ResponseInterface $response)
    {
        $this->container = $container;
        $this->request = $request;
        $this->response = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        echo "checkUser".PHP_EOL;
        $uid = $this->request->route('uid',0);
        if($uid == 11){
            return $this->response->json(['result' => 'is a secret']);
        }
        return $handler->handle($request);
    }
}