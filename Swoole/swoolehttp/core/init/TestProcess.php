<?php


namespace Core\init;


use Swoole\Process;

class TestProcess
{
    private $md5File;

    public function run()
    {
        return new Process(function () {
            while (true) {
                sleep(3);
                // 判断文件变动自动重启
                $files = glob(__DIR__ . '/../../*.php');
                $md5FileArr = [];
                foreach ($files as $file) {
                    $md5FileArr[] = md5_file($file);
                }
                $md5Value = md5(implode("", $md5FileArr));
                if ($this->md5File == "") {
                    $this->md5File = $md5Value;
                    continue;
                }
                // 文件改动
                if (strcmp($this->md5File, $md5Value) !== 0) {
                    echo 'reloading...' . PHP_EOL;
                    $getPid = intval(file_get_contents(__DIR__ . "/../../aliliin.pid"));
                    if ($getPid && trim($getPid) != 0) {
                        Process::kill($getPid, SIGUSR1);
                    }
                    $this->md5File = $md5Value;
                    echo 'reload end...' . PHP_EOL;
                }
            }
        });
    }
}