<?php

namespace App\Pool;

use Swoole\Coroutine\Channel;

abstract class DBPool
{
    private $min;
    private $max;
    private $conns;

    abstract protected function newDB();

    public function __construct($min = 5, $max = 10)
    {
        $this->min = $min;
        $this->max = $max;
        $this->conns = new Channel($this->max);
    }

    public function initPool()
    {
        for ($i = 0; $i < $this->min; $i++) {
            $this->conns->push($this->newDB());
        }

    }
}