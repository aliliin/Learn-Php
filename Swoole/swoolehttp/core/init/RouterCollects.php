<?php


namespace Core\init;

use Core\annotations\Bean;

/**
 * @Bean()
 * Class RouterCollects
 * @package Core\init
 */
class RouterCollects
{
    public $routes = [];

    /**
     * 搜集路由
     * @param $method
     * @param $url
     * @param $handler
     */
    public function addRouter($method, $url, $handler)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $url,
            'handler' => $handler
        ];
    }

    public function getDispatcher()
    {
        return \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) {
            foreach ($this->routes as $route) {
                $r->addRoute($route['method'], $route['uri'], $route['handler']);
            }
        });
    }

}