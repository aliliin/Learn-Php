<?php
// 命令行启动服务 测试
use \Swoole\Http\Server;
use \Swoole\Process;

if ($argc == 2) {
    $cmd = $argv[1];
    if ($cmd == 'start') {
        $http = new Server('127.0.0.1', 9501);

        $http->set(array(
            'worker_num' => 1,
            'daemonize' => false,
        ));

        $http->on('request', function ($req, $res) {

        });


        $http->on('start', function (Server $server) {
            $masterPid = $server->master_pid;
            file_put_contents("./Aliliin.pid", $masterPid);
        });
        $http->start();
    } elseif ($cmd == 'stop') {
        // 获取上一次程序运行的 master ID
        $getPid = intval(file_get_contents("./Aliliin.pid"));
        if ($getPid && trim($getPid) != 0) {
            Process::kill($getPid);
        }
    }
}


