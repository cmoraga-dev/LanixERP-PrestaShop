<?php


namespace cl\lanixerp\pos\modelo\saldo;

use JMS\Serializer\Annotation as Serializer;
class Key
{
    /** @Serializer\Type ("string") */
    private $codigoProducto;

    /** @Serializer\Type ("string") */
    private $codigoBodega;

    /** @Serializer\Type ("integer") */
    private $serie;

    /** @Serializer\Type ("integer") */
    private $lote;

    /**
     * @return mixed
     */
    public function getCodigoProducto()
    {
        return $this->codigoProducto;
    }

    /**
     * @param mixed $codigoProducto
     */
    public function setCodigoProducto($codigoProducto)
    {
        $this->codigoProducto = $codigoProducto;
    }

    /**
     * @return mixed
     */
    public function getCodigoBodega()
    {
        return $this->codigoBodega;
    }

    /**
     * @param mixed $codigoBodega
     */
    public function setCodigoBodega($codigoBodega)
    {
        $this->codigoBodega = $codigoBodega;
    }

    /**
     * @return mixed
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * @param mixed $serie
     */
    public function setSerie($serie)
    {
        $this->serie = $serie;
    }

    /**
     * @return mixed
     */
    public function getLote()
    {
        return $this->lote;
    }

    /**
     * @param mixed $lote
     */
    public function setLote($lote)
    {
        $this->lote = $lote;
    }


}
