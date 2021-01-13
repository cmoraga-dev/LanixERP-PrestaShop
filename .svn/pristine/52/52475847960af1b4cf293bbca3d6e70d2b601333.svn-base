<?php
require (dirname(__FILE__)."/vendor/autoload.php");
include_once(dirname(__FILE__) . '/../../config/config.inc.php');

/*
if (php_sapi_name() !== 'cli'){
    echo 'Forbidden';
    return;
}
*/

if (!extension_loaded('ev')) {
    echo 'No se ha detectado extension ev en sistema';
    return;
}
$lx = SingletonTimer::getInstance();
