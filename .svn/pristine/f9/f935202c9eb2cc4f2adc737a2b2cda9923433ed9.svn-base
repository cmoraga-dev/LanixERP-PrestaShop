<?php

use Lanix\LxRestClient;
use Lanix\LxDeserializer;
use Lanix\LxUtilDB;
use Lanix\LxCommands;
use Lanix\LxControlSync;
use PrestaShop\PrestaShop\Adapter\Entity\PrestaShopLogger;
use PrestaShop\PrestaShop\Adapter\Entity\Search;
use Doctrine\Common\Annotations\AnnotationRegistry;
class SingletonTimer {
    private static $instance = null;
    private $count = "";

    private function __construct() {

        $w1 = new \EvTimer(0, 120, function(){

    //        AnnotationRegistry::registerLoader('class_exists');
            $actualizador = new LxControlSync();
            $actualizador->run();

        });

        \Ev::run();

    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new SingletonTimer();
        }
        return self::$instance;
    }

    public static function runCommands (){

        $fullDate = LxUtilDB::getSyncDate('comandos');

        $params = array(
            'fecModif' => date("Ymd", strtotime($fullDate)),
            'horaModif' => date("His", strtotime($fullDate))
        );

        $data = LxRestClient::getData('comandos', $params);

        $xml = simplexml_load_string($data);

        foreach ($xml->comandos as $command){
            switch ($command->nombre){
                case 'CTA_BORRAR':
                    LxCommands::deleteUser($command);
                    break;
                case 'PROD_BORRAR':
                    LxCommands::deleteProduct($command);
                    break;
                case 'PROD_CAMBIOCOD':
                    LxCommands::updateLxCodProduct($command);
                    break;
                case 'PRE_BORRAR':
                    LxCommands::deletePriceProduct($command);
                    break;
                case 'DOC_ENVIAR':
                    //dump($command);
                    break;
                default:
                    break;

            }
        }
        LxUtilDB::updateTblSync('comandos');

    }
}

