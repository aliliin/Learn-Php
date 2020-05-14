<?php

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/app/config/define.php';

$db = new \Core\init\MyDB();

//$db->setDbSource('salve');
$ret = $db->table('users')->get();

var_dump($ret);