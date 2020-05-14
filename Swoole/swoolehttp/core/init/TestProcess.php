<?php


namespace Core\init;


use Core\helper\FilesHelper;
use Swoole\Process;

class TestProcess
{
    private $md5File;

    public function run()
    {
        return new Process(function () {
            while (true) {
                sleep(3);

                $md5Value = FilesHelper::getFileMd5(ROOT_PATH . "/app/*", '/app/config');

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