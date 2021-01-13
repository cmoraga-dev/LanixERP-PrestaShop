<?php

require (dirname(__FILE__)."/vendor/autoload.php");
include_once(dirname(__FILE__) . '/../../config/config.inc.php');

if (!extension_loaded('ev')) return;
/*
if (php_sapi_name() !== 'cli'){
    echo 'Forbidden';
    return;
}
*/


$pid = Configuration::get('LX_PID'); //false if not found

if ($pid == false | !file_exists("/proc/$pid")) {
    echo "not running";
}else {
    echo "running";
}
