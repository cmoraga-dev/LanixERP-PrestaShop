<?php


namespace Lanix;
use JMS\Serializer\Annotation as Serializer;

class Key
{
    /** @Serializer\Type ("integer") */
    private $codigoTipo;

    /**
     * @Serializer\Type ("integer")
     */
    private $codigo;

    /**
     * @Serializer\Type ("integer")
     */
    private $codigoSucursal;

    /**
     * key constructor.
     */
    public function __construct()
    {
        $this->codigoTipo = 1;
        $this->codigoSucursal = 0;
    }


    public function getCodigoTipo()
    {
        return $this->codigoTipo;
    }

    public function setCodigoTipo($codigoTipo)
    {
        $this->codigoTipo = $codigoTipo;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function getCodigoSucursal()
    {
        return $this->codigoSucursal;
    }


    public function setCodigoSucursal($codigoSucursal)
    {
        $this->codigoSucursal = $codigoSucursal;
    }


}
