<?php

namespace App\controller;

use Core\annotations\Bean;
use Core\annotations\RequestMapping;
use Core\annotations\Value;
use Core\Http\Request;
use Core\http\Response;

/**
 * @Bean(name="Aliliin")
 */
class UserController
{
    /**
     * @Value(name="version")
     */
    public $version;

    /**
     * @RequestMapping(value="/index/{uid:\d+}")
     */
    public function index(string $name, int $uid)
    {
        return "Aliliin" . $uid;
    }

    /**
     * @RequestMapping(value="/user")
     */
    public function user()
    {
        return "user";
    }

    /**
     * @RequestMapping(value="/test/{uid:\d+}")
     */
    public function test(Request $request, int $uid, Response $response)
    {
//        $response->redirect("http://www.baidu.com");

//        $response->writeHtml("你好吗");
//        $response->testWrite("abc");
//        $response->writeHttpStatus('200', '404');
//        var_dump($request->getQueryParams());
//        return "user" . $uid;
        return [
            "user" => $uid,
            "value" => '测试中文',
            "测试自动加载" => true,
        ];
    }

}