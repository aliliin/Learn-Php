<?php

namespace App\controller;

use App\models\ProductsModel;
use App\models\UsersModel;
use Core\annotations\Bean;
use Core\annotations\DB;
use Core\annotations\Redis;
use Core\annotations\RequestMapping;
use Core\annotations\Value;
use Core\Http\Request;
use Core\http\Response;
use Core\init\MyDB;
use DI\Annotation\Inject;
use Swoole\Coroutine\Channel;

/**
 * @Bean(name="Aliliin")
 */
class RedisController
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
     * @Redis
     * @RequestMapping(value="/redis")
     */
    public function redis()
    {
        return ['redis 测试'];
    }

    /**
     * @Redis
     * @RequestMapping(value="/redis/test")
     */
    public function redistest()
    {
        $products = ProductsModel::take(5)->skip(5)->get()->toArray();
        var_dump($products);
        return $products;

    }

    /**
     * @Redis(key="#1",prefix="userid",timeout="10",type="hash",incr="usercount")
     * @RequestMapping(value="/testredis/{uid:\d+}")
     */
    public function testredis(Request $request, int $uid, Response $response)
    {
        return UsersModel::find($uid);
    }

    /**
     * @Redis(prefix="hproduct",key="#p_id",type="hash")
     * @RequestMapping(value="/products")
     */
    public function parsePlaceholders()
    {
        return ProductsModel::all();
    }

    /**
     * 库存
     * @Redis(key="p_id",prefix="stock",type="sortedset",member="p",score="p_stock",coroutine="true")
     * @RequestMapping(value="/stock")
     */
    public function stock(Request $request, int $uid, Response $response)
    {

//        return ProductsModel::all();
        // 协程 的方式，加入库存
        $channel = new Channel(6);
        $pageSize = 5;
        for ($i = 0; $i < 6; $i++) {
            go(function () use ($i, $pageSize, $channel) {
                $products = ProductsModel::take($pageSize)->skip($i * $pageSize)->get()->toArray();
                $channel->push($products);
            });
        }
        return $channel;
    }
}