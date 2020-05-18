<?php

namespace App\Pool;

use Swoole\Coroutine\Channel;
use Swoole\Timer;

abstract class DBPool
{
    private int $min;
    private int $max;
    private Channel $conns;
    private int $count = 0; // 当前链接总数
    private int $linkTimeFree = 10;// 链接空闲时间（秒）


    abstract protected function newDB();

    public function __construct(int $min = 5, int $max = 10)
    {
        $this->min = $min;
        $this->max = $max;
        $this->conns = new Channel($this->max);
    }

    /**
     * 根据最小链接数，初始化链接池
     */
    public function initPool()
    {
        for ($i = 0; $i < $this->min; $i++) {
            $this->addDBToPool();
        }
        Timer::tick(2000, function () {
            $this->cleanPool();
        });
    }

    /**
     * 取出
     * @return mixed
     */
    public function getConnection()
    {
        // 判断当前链接是否存在
        if ($this->conns->isEmpty()) {
            // 小于最大链接数
            if ($this->count < $this->max) {
                $this->addDBToPool();
                return $this->conns->pop();
            } else {
                return $this->conns->pop(5);
            }
        } else {
            $getObject = $this->conns->pop();
            $getObject->usedTime = time();
            return $getObject;
        }
    }


    /**
     * 放回
     * @param $conn
     */
    public function close($conn)
    {
        if ($conn) {
            $this->conns->push($conn);
        }
    }

    /**
     * 新增链接
     */
    public function addDBToPool()
    {
        try {
            $this->count++;
            $DB = $this->newDB();
            if (!$DB) throw new \Exception("DB 创建错误");
            $dbObjNull = new \StdClass();
            $dbObjNull->usedTime = time();
            $dbObjNull->db = $DB;
            $this->conns->push($DB);
        } catch (\Exception $exception) {
            $this->count--;
        }
    }

    /**
     * @param int $linkTimeFree
     */
    public function setLinkTimeFree(int $linkTimeFree): void
    {
        $this->linkTimeFree = $linkTimeFree;
    }

    public function cleanPool()
    {
        if ($this->conns->length() <= $this->min && $this->conns->length() < $this->max) {
            return;
        }
        echo "开始执行清理" . PHP_EOL;
        $dbBak = [];
        while (true) {
            if ($this->conns->isEmpty()) break;
            $obj = $this->conns->pop(0.1);
            if ($this->count > $this->min && (time() - $obj->usedTime) > $this->linkTimeFree) {
                $this->count--;
            } else {
                $dbBak[] = $obj;
            }
            foreach ($dbBak as $item) {
                $this->conns->push($item);
            }
            echo "当前链接数" . $this->count . PHP_EOL;
        }
    }
}