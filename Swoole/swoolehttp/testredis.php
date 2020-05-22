<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/config/define.php';
// lua 脚本
$redis = new \Redis();
$redis->connect('127.0.0.1', 6379);

$setName = <<<script
return redis.call("set","name","aliliin1");
script;

$res = $redis->eval($setName);
var_dump($res);

$getName = <<<script
return redis.call("get","name");
script;
$get = $redis->eval($getName);


$setNames = <<<script
return redis.call("set",KEYS[1],ARGV[1]);
script;
$res = $redis->eval($setNames, ['names', 'alilins'], 1);


/**
 * eval($lua,$data,$num)
 * $lua 要执行的 lua 脚本
 * $data 参数（数组）
 * $num 表示第二个参数数组中有几个是参数（数组其他剩下来的是附加参数）
 * 参数 keys[1] keys[n] 来获取 附加参数 argv[1] 来获取
 */