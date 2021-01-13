<?php
$_SERVER['REQUEST_METHOD'] = "GET"; // Fix for SSL redirection
$_SERVER['SERVER_SOFTWARE'] = "Apache/2.4.29 (Ubuntu)"; // Fix for WebPay


include_once(dirname(__FILE__) . '/../../config/config.inc.php');
require (dirname(__FILE__)."/vendor/autoload.php");
use Lanix\LxControlSync;



/*
if (php_sapi_name() !== 'cli'){
    echo 'Forbidden';
    return;
}
*/

// Init Shop context, required some operation will fail without it
// adust accordly to multistore PS >= 1.6
Shop::setContext(Shop::CONTEXT_SHOP);
// Init PS context, some modules require that this context was initialized and with correct data
// some core function fired in the admin require at least a employee
define ('PS_DEFAULT_EMPLOYEE', 1);
$psContext = Context::getContext();
$psContext->shop = Shop::initialize();
if (!$psContext->employee) {
    $psContext->employee = new Employee(PS_DEFAULT_EMPLOYEE);
}

$actualizador = new LxControlSync();
$actualizador->run();
