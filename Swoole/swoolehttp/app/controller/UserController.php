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
        $user = new UsersModel();
        $user->username = 'saved';
        $user->password = 'shahha1';
        $user->save();
        return [$user->id];

        /**
         *
         */
    }

    /**
     * @RequestMapping(value="/testdelete/{uid:\d+}")
     */
    public function testDelete(int $uid)
    {
        UsersModel::find($uid)->delete();
        return [$uid . '删除 成功'];
    }


    /**
     * @RequestMapping(value="/testupdate/{userid:\d+}")
     */
    public function testUpdate(int $userid)
    {
        $user = UsersModel::find($userid);
        $user->username = 'Swoole';
        $user->save();
        return [$user->id . '更新 成功'];
    }

    /**
     * @RequestMapping(value="/translation")
     */
    public function translation()
    {
        $tx = $this->db->Begin();
        $user = UsersModel::find(45);
        $user->password = 123456;
        $user->save();
        $user = UsersModel::find(47);
        $user->password = 654211;
        $user->save();
        $tx->rollback();
        return ['测试事务 成功'];
    }

    /**
     * @RequestMapping(value="/testtranslation")
     */
    public function testTranslation()
    {
        $tx = $this->db->Begin();
        $user = new UsersModel();
        $user->username = '测试添加数据，事务添加';
        $user->password = 123456;
        $user->save();
        $tx->commit();
        sleep(5);
        return ['测试事务 成功'];
    }

    /**
     * @RequestMapping(value="/testdb2")
     */
    public function testdb2()
    {
//        return UsersModel::first();
        return $this->db->select('select sleep(5)');
    }

    /**
     * @RequestMapping(value="/testdb1")
     */
    public function testdb1()
    {
//        return UsersModel::all();
        return $this->slaveDB->table('users')->get();
    }

    /**
     *  测试数据库 事务
     * @RequestMapping(value="/testTrans2")
     */
    public function testTrans2()
    {
        $translation = $this->db->Begin();
        {
            $translation->table('users')->insert([
                [
                    'username' => 'shahha1',
                    'password' => 'shahha111',
                    'status' => 1,
                    'age' => 1,
                    'sex' => '1'
                ]
            ]);
            $translation->table('users')->insert([
                [
                    'username' => 'shahha2',
                    'password' => 'shahha112',
                    'status' => 1,
                    'age' => 1,
                    'sex' => '1'
                ]
            ]);
            sleep(10);
        }
        $translation->Commit();
//        $this->db->Rollback();

        return [123];
    }

    /**
     * @RequestMapping(value="/testTrans1")
     */
    public function testTrans1()
    {
        $translation = $this->db->Begin();

        $translation->table('users')->insert([
            [
                'username' => 'shahh2323a',
                'password' => 'shahha111',
                'status' => 1,
                'age' => 1,
                'sex' => '1'
            ]
        ]);
        $translation->Commit();
//        $this->db->Rollback();

        return [333];
    }
}