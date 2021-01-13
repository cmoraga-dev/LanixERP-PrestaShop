<?php

require (dirname(__FILE__)."/vendor/autoload.php");
include_once(dirname(__FILE__) . '/../../config/config.inc.php');
use Lanix\LxUtilDB;

/*
if (php_sapi_name() !== 'cli'){
    echo 'Forbidden';
    return;
}
*/

$data = LxUtilDB::selectAll('lx_tabla_generica', '`descripcion`','codigo_tabla = "63"');
foreach ($data as $key=>$value){
    echo $value['descripcion'].',';
}

