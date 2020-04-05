<?php

// 初始化 WebSocket 服务器， 在本地监听 8000 端口

use Swoole\WebSocket\Server;

$server = new Swoole\WebSocket\Server('127.0.0.1', 8000);

// 建立建立连接的时候触发
$server->on('open', function (Server $server, $request) {
    echo 'Server: handshake success with fd' . $request->fd . "\n";
});

// 接收到消息时 触发推送
$server->on('message', function (Server $server, $frame) {
    echo 'receive from ' . $frame->fd . ':' . $frame->data . ', opcode:' . $frame->opcode . ',fin: ' . $frame->finish . "\n";
    $server->push($frame->fd, 'this is server');
});

// 关闭 webSocket 连接时触发
$server->on('close', function ($ser, $fd) {
    echo 'client' . $fd . "closed\n";
});

// 启动
$server->start();