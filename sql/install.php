<?php /** @noinspection SqlNoDataSourceInspection */
/**
* 2007-2020 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2020 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
$sql = array();
$sql[] ='CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'lx_customers` (
                  `lx_codigo` varchar(20),
                  `id_pshop` int,
                  `vch_rut` varchar(12),
                  `int_rut` int,
                  `dv` varchar(1),
                    PRIMARY KEY  (`lx_codigo`,`id_pshop`)
                  ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8; ';

$sql[] ='CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'lx_productos` (
                  `lx_codigo` varchar(20),
                  `id_pshop` int,
                    PRIMARY KEY  (`lx_codigo`,`id_pshop`)
                  ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8; ';


$sql[] ='CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'lx_tabla_generica` (
                  `codigo_tabla` varchar (20),
                  `codigo` varchar (20),
                  `id_pshop` varchar (20),
                  `descripcion` varchar (30),
                  PRIMARY KEY  (`codigo_tabla`,`codigo`,`id_pshop`)
                  ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8; ';


$sql[] ='CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'lx_sincronizacion` (
                  `tabla` varchar (20),
                  `fecha` datetime,
                  PRIMARY KEY  (`tabla`)
                  ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8; ';

$sql[] ='CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'lx_webpay` (
                  `ps_reference` varchar (20),
                  `autorizacion` varchar (20),
                  `tipo` varchar (20),
                  `cuotas` int ,
                  PRIMARY KEY  (`ps_reference`)
                  ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8; ';

$sql[] ='CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'lx_ventas` (
                  `ps_reference` varchar (20),
                  `tipo_doc` varchar (20),
                  `folio` varchar (20),
                  `xml` varchar (2000),
                  `estado`  varchar (20),
                  PRIMARY KEY  (`ps_reference`,`folio`)
                  ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8; ';



foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
