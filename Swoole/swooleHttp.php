<?php

// 需要开启 http 服务进行监听自己设置的端口
$server = new swoole_http_server('127.0.0.1', 9501);

// 服务器启动后返回响应
$server->on('start', function ($server) {
    echo "Swoole Http Server is Started at http://127.0.0.1:9501 \n";
});

// 向服务器发送请求时返回响应
// 可以获取请求参数，也可以设置响应头和响应内容
$server->on('request', function ($request, $response) {
    $response->header('Content-Type', "text/plain");
    $response->end("Hello World Http \n");
});

// 启动 Http 服务
$server->start();
