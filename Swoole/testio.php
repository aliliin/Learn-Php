<?php
ini_set("use_shortname", "'On'");
echo phpinfo();
function testIO(int $time)
{
    echo "开始工作\n";
    sleep($time);
    echo "结束工作\n";
}

$start = time();

go(function(){
    testIO( 1);
});


go(function(){
    testIO( 2);
});

echo "耗时 ： "  (time()) . '-'.$start ."\n";