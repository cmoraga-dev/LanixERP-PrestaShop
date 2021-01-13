<?php
namespace Lanix;
use PrestaShop\PrestaShop\Adapter\Entity\Db;
use PrestaShop\PrestaShop\Adapter\Entity\DbQuery;
use PrestaShop\PrestaShop\Adapter\Entity\Context;
use PrestaShop\PrestaShop\Adapter\Entity\Product;
use PrestaShop\PrestaShop\Adapter\Entity\Shop;
use PrestaShop\PrestaShop\Adapter\Entity\Combination;

class LxUtilDB
{

    //all tbl
    public static function saveData ($table, $data){
       return Db::getInstance()->insert(
            $table,
            $data,
            false,
            true,
            Db::REPLACE
        );
    }

    public static function deleteWhere($table, $condition)
    {
        return Db::getInstance()->delete($table, $condition);
    }


    public static function selectWhere ($table, $select, $condition = null){

        $sql = new DbQuery();
        $sql->from($table);
        $sql->select($select);
        if ($condition != null) $sql->where($condition);

        return Db::getInstance()->getValue($sql);
    }

    public static function selectAll ($table, $select, $condition = null){

        $sql = new DbQuery();
        $sql->from($table);
        $sql->select($select);
        if ($condition != null) $sql->where($condition);

        return Db::getInstance()->executeS($sql);
    }

    //tlb Products

    public static function ProductSearchByName ($id_lang, $query, Context $context = null){
        if (!$context) {
            $context = Context::getContext();
        }

        $sql = new DbQuery();
        $sql->select('p.`id_product`, pl.`name`, p.`ean13`, p.`isbn`, p.`upc`, p.`active`, p.`reference`, m.`name` AS manufacturer_name, stock.`quantity`, product_shop.advanced_stock_management, p.`customizable`');
        $sql->from('product', 'p');
        $sql->join(Shop::addSqlAssociation('product', 'p'));
        $sql->leftJoin(
            'product_lang',
            'pl',
            'p.`id_product` = pl.`id_product`
            AND pl.`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang('pl')
        );
        $sql->leftJoin('manufacturer', 'm', 'm.`id_manufacturer` = p.`id_manufacturer`');

        $where = 'pl.`name` = \'%' . pSQL($query) . '%\'
        OR EXISTS(SELECT * FROM `' . _DB_PREFIX_ . 'product_supplier` sp WHERE sp.`id_product` = p.`id_product` AND `product_supplier_reference` = \'%' . pSQL($query) . '%\')';

        $sql->orderBy('pl.`name` ASC');

        if (Combination::isFeatureActive()) {
            $where .= ' OR EXISTS(SELECT * FROM `' . _DB_PREFIX_ . 'product_attribute` `pa` WHERE pa.`id_product` = p.`id_product` AND (pa.`reference` = \'%' . pSQL($query) . '%\'
            OR pa.`upc` = \'%' . pSQL($query) . '%\'))';
        }
        $sql->where($where);
        $sql->join(Product::sqlStock('p', 0));

        $result = Db::getInstance()->executeS($sql);

        if (!$result) {
            return false;
        }

        $results_array = array();
        foreach ($result as $row) {
            $row['price_tax_incl'] = Product::getPriceStatic($row['id_product'], true, null, 2);
            $row['price_tax_excl'] = Product::getPriceStatic($row['id_product'], false, null, 2);
            $results_array[] = $row;
        }

        return $results_array;

    }

    // tbl Products , tbl Customers

    /**
     * @param $table
     * @param $lxCod
     * @return false|string|null
     */
    public static function getIdPShopByLxCod ($table, $lxCod){

        $sql = new DbQuery();
        $sql->from($table);
        $sql->select('id_pshop');
        $sql->where('lx_codigo = "'.$lxCod.'"');
        return Db::getInstance()->getValue($sql);
    }


    //tbl genericas
    public static function selectFromTblGenericaWhere ($select,$codtabla, $condition){

        $sql = new DbQuery();
        $sql->from('lx_tabla_generica');
        $sql->select($select);
        $sql->where('codigo_tabla = "'.$codtabla.'" AND '.$condition);
        return Db::getInstance()->getValue($sql);
    }



    public static function getIdPshopFromTblGenericaByCodigo ($codtabla, $codigo){

        $sql = new DbQuery();
        $sql->from('lx_tabla_generica');
        $sql->select('id_pshop');
        $sql->where('codigo_tabla = "'.$codtabla.'" AND codigo = "'.$codigo.'"');
        return Db::getInstance()->getValue($sql);
    }

    public static function getCodigoFromTblGenericaByIdPShop ($codtabla, $idPShop){

        $sql = new DbQuery();
        $sql->from('lx_tabla_generica');
        $sql->select('codigo');
        $sql->where('codigo_tabla = "'.$codtabla.'" AND id_pshop = "'.$idPShop.'"');
        return Db::getInstance()->getValue($sql);
    }


    //tbl customers
    public static function getRutById ($id){
        $sql = new DbQuery();
        $sql->from('lx_customers');
        $sql->select('vch_rut');
        $sql->where('id_pshop = '. (int)$id);
        return Db::getInstance()->getValue($sql);
    }


    public static function getIdPersonaByLxCodigo ($lxCodigo) {

        $sql = new DbQuery();
        $sql->from('lx_customers');
        $sql->select('id_pshop');
        $sql->where('lx_codigo = "'. $lxCodigo.'"');
        return Db::getInstance()->getValue($sql);

    }

    /**
     * @param $rut
     * @return false|string|null
     */
    public static function checkRutExists ($rut){
        $sql = new DbQuery();
        $sql->from('lx_customers');
        $sql->select('vch_rut');
        $sql->where('vch_rut = "'. $rut.'"');
        return Db::getInstance()->getValue($sql);
    }

    public static function saveLxCustomers ($id, $lxCodigo, $rut, $numRut, $dvRut) {

        Db::getInstance()->insert(
                'lx_customers',
                [
                    'lx_codigo' => $lxCodigo,
                    'id_pshop' => (int)$id,
                    'vch_rut' => strtoupper($rut),
                    'int_rut' => $numRut,
                    'dv' => (int)$dvRut
                ],
                false,
                true,
                Db::REPLACE
            );

    }


    //tbl sincronizacion
    public static function updateTblSync ($resource){
        return Db::getInstance()->insert(
            'lx_sincronizacion',
            [
                'tabla' => $resource,
                'fecha' => date('Y-m-d H:i:s')
            ],
            false,
            true,
            Db::REPLACE);
    }

    public static function getSyncDate ($resource){
        $sql = new DbQuery();
        $sql->from('lx_sincronizacion');
        $sql->select('fecha');
        $sql->where('tabla = "'. $resource.'"');
        return Db::getInstance()->getValue($sql);

    }


}
