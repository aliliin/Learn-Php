<?php

define('ROOT_PATH', dirname(dirname(__DIR__)));

$GLOBALS_CONFIGS = [
    'db'=>require_once(__DIR__."/db.php"),
    'DBPool'=>require_once(__DIR__ . "/DBPool.php"),
];