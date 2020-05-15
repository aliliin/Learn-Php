<?php

namespace App\controller;

use App\models\UsersModel;
use Core\annotations\Bean;
use Core\annotations\DB;
use Core\annotations\RequestMapping;
use Core\annotations\Value;
use Core\Http\Request;
use Core\http\Response;
use Core\init\MyDB;
use DI\Annotation\Inject;

/**
 * @Bean(name="Aliliin")
 */
class UserController
{
    /**
     * @DB()
     * @var MyDB
     */
    private $db;

    /**
     * @DB(source="slave")
     * @var MyDB
     */
    private $slaveDB;

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

    /**
     * @RequestMapping(value="/testdb")
     */
    public function testdb()
    {
        return UsersModel::first();
//        return $this->db->table('users')->get();
    }

    /**
     * @RequestMapping(value="/testdb1")
     */
    public function testdb1()
    {
        return UsersModel::all();
        return $this->slaveDB->table('users')->get();
    }


}