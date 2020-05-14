<?php


namespace Core\server;

use Core\BeanFactory;
use Core\init\TestProcess;
use FastRoute\Dispatcher;
use Swoole\Http\Request;
use Swoole\Http\Response;
use \Swoole\Http\Server;
use \Swoole\Process;


class HttpServer
{
    private $server;

    private $dispatcher;

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
        BeanFactory::init();
        $this->dispatcher = BeanFactory::getBean("RouterCollects")->getDispatcher();
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

        $myRequest = \Core\Http\Request::init($request);
        $myResponse = \Core\http\Response::init($response);
        $routeInfo = $this->dispatcher->dispatch($myRequest->getMethod(), $myRequest->getUri());
        // [1,$dispatcher,$var] 有三个值
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                $response->status(404);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                $response->status(405);
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];// ... call $handler with $vars
                $extVars = [$myRequest, $myResponse];
                // 设置响应 body 部分
                $myResponse->setBody($handler($vars, $extVars));
                $myResponse->end();
                break;
        }
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