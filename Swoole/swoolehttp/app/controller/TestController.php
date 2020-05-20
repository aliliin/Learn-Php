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
class TestController
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
     * @RequestMapping(value="/testTrans3")
     */
    public function testTrans3()
    {
        $this->db->table('users')->insert([
            [
                'username' => 'shahh2323a11111111',
                'password' => 'shahha111',
                'status' => 1,
                'age' => 1,
                'sex' => '1'
            ]
        ]);
        return [13123];
    }

}