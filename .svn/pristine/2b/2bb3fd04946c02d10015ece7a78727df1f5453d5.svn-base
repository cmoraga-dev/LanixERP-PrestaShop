<?php


namespace Lanix;
use PrestaShop\PrestaShop\Adapter\Configuration;
use PrestaShop\PrestaShop\Adapter\Entity\Product;
use PrestaShop\PrestaShop\Adapter\Entity\Customer;

class LxCommands
{
    //CTA_BORRAR
    public static function deleteUser ($params){
        $params = (string)$params->parametros;
        $arrayParams = explode(',', $params);
        $code = $arrayParams[1];

        //get idPshop
        $idPshop = LxUtilDB::selectWhere('lx_customers', 'id_pshop','lx_codigo = "'.$code.'"');

        //no existe en sistema (por ej por falta de correo, ya se borró, etc)
        if ($idPshop == false) return;
        $customer = new Customer($idPshop);
        $customer->delete();
        LxUtilDB::deleteWhere('lx_customers','lx_codigo = "'.$code.'"');
    }

    //PROD_BORRAR
    public static function deleteProduct ($params){

        $code = (string)$params->parametros;
        $idPshop = LxUtilDB::selectWhere('lx_productos', 'id_pshop','lx_codigo = "'.$code.'"');
        $product = new Product($idPshop);
        $product->delete();
        LxUtilDB::deleteWhere('lx_productos','lx_codigo = "'.$code.'"');
    }

    //PROD_CAMBIOCOD
    public static function updateLxCodProduct ($params){

        $params = (string)$params->parametros;
        $arrayParams = explode(',', $params);
        $oldCode = $arrayParams[0];

        //get idPshop
        $idPshop = LxUtilDB::selectWhere('lx_productos', 'id_pshop','lx_codigo = "'.$oldCode.'"');

        //ya está manejado con prevenir nombres duplicados
        if ((new Configuration)->get('LX_PREVENT_DUPLICATES_PRODUCTS') == 'false') {
            $product = new Product($idPshop);
            $product->delete();
        }

        //borrar antigua relacion
        LxUtilDB::deleteWhere('lx_productos','lx_codigo = "'.$oldCode.'"');

    }

    //PRE_BORRAR - borra elemento lista de precios
    //preguntar por parametros a Pedro
    public static function deletePriceProduct ($params)
    {
        $params = (string)$params->parametros;
        $arrayParams = explode(',', $params);
        $priceList = $arrayParams[0];
        $productCode = $arrayParams[1];

        //si operacion corresponde a lista de precios usada actualmente
        //se edita producto: precio 0 y desactivado
        if ($priceList == (new Configuration)->get('LX_COD_LOCAL')) {
            $idPshop = LxUtilDB::selectWhere('lx_productos', 'id_pshop','lx_codigo = "'.$productCode.'"');
            $product = new Product($idPshop);
            $product->price = 0;
            $product->active = false;
            $product->save();

        }
    }

    //DOC_ENVIAR(sistema, tipo, folio) -> Fuerza el reenvío de un documento.
    public static function forceSubmit (){
        //
    }
}
