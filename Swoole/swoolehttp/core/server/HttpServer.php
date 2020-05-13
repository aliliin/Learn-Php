<?php


namespace Core\server;

use Core\init\TestProcess;
use Swoole\Http\Request;
use Swoole\Http\Response;
use \Swoole\Http\Server;
use \Swoole\Process;


class HttpServer
{
    private $server;

    public function __construct()
    {
        $this->server = new Server('127.0.0.1', 9501);

        $this->server->set(array(
            'worker_num' => 1,
            'daemonize' => false,
        ));

        $this->server->on('request', [$this, "onRequest"]);

        $this->server->on('start', [$this, "onStart"]);

        $this->server->on('ShutDown', [$this, "onShutDown"]);

        $this->server->on('workerStart', [$this, "onWorkerStart"]);

        $this->server->on('managerStart', [$this, "onManagerStart"]);

    }

    public function onWorkerStart(Server $server, $workerId)
    {
        require_once(__DIR__ . '/../../testaliliin.php');
        // 设置进程名称
        //  cli_set_process_title("aliliin worker");
    }

    public function onManagerStart(Server $server)
    {
        // cli_set_process_title("aliliin manager");
    }

    public function run()
    {
        $process = new TestProcess();
        $this->server->addProcess($process->run());
        $this->server->start();
    }

    public function onRequest(Request $request, Response $response)
    {
        $response->end(index());
    }

    public function onStart(Server $server)
    {
//        cli_set_process_title("aliliin master");
        file_put_contents(__DIR__ . "/../../aliliin.pid", $server->master_pid);
    }

    public function onShutDown(Server $server)
    {
        echo '关闭服务' . PHP_EOL;
        unlink(__DIR__ . "/../../aliliin.pid");
    }


}