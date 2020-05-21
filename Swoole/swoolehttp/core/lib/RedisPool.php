<?php


namespace Core\lib;


use Swoole\Coroutine\Channel;

abstract class RedisPool
{
    private int $min;
    private int $max;
    private Channel $conns;
    private int $count = 0; // 当前链接总数
    private int $linkTimeFree = 10;// 链接空闲时间（秒）

    abstract protected function newRedis();

    public function __construct(int $min = 5, int $max = 10, int $linkTimeFree = 10)
    {
        $this->min = $min;
        $this->max = $max;
        $this->linkTimeFree = $linkTimeFree;
        $this->conns = new Channel($this->max);

        // 构造方法直接初始化 DB 链接
        for ($i = 0; $i < $this->min; $i++) {
            // 统一调用
            $this->addRedisToPool();
        }
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * 根据最小链接数，初始化链接池
     */
    public function initPool()
    {
//        Timer::tick(2000, function () {
//            $this->cleanPool();
//        });
    }

    /**
     * 取出
     * @return mixed
     */
    public function getConnection()
    {
        $getObject = false;
        // 判断当前链接是否存在
        if ($this->conns->isEmpty()) {
            // 小于最大链接数
            if ($this->count < $this->max) {
                $this->addRedisToPool();
                return $this->conns->pop();
            } else {
                return $this->conns->pop(5);
            }
        } else {
            $getObject = $this->conns->pop();
        }
        if ($getObject) {
            $getObject->usedTime = time();
        }
        return $getObject;
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
    public function addRedisToPool()
    {
        try {
            $this->count++;
            $DB = $this->newRedis();
            if (!$DB) throw new \Exception("DB 创建错误");
            $dbObjNull = new \StdClass();
            $dbObjNull->usedTime = time();
            $dbObjNull->redis = $DB;

            $this->conns->push($dbObjNull);
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
        if ($this->conns->length() <= $this->min && $this->conns->length() < intval($this->max * .6)) {
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