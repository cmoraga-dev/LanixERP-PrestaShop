<?php

namespace cl\lanixerp\pos\modelo\saldo;

use JMS\Serializer\Annotation as Serializer;
use PrestaShop\PrestaShop\Adapter\Entity\StockAvailable;
use Lanix\LxUtilDB;
/** @Serializer\XmlRoot("saldo") */
class Saldo
{
    /** @Serializer\Type ("integer") */
    private $stock;

    /**
     * @Serializer\XmlKeyValuePairs
     * @Serializer\Type("cl\lanixerp\pos\modelo\saldo\Key")
     **/
    private $key;

    public function getStock()
    {
        return $this->stock;
    }

    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function save (){
      $productId =  LxUtilDB::getIdPShopByLxCod('lx_productos',$this->key->getCodigoProducto());
      if ($productId == null || $productId == false) return;
      StockAvailable::setQuantity((int)$productId,0,(int)$this->stock);
      LxUtilDB::updateTblSync('saldos');
    }

}
