<?php


namespace Lanix;

use JMS\Serializer\Annotation as Serializer;
class SimplePago
{
    /** @Serializer\Type ("string") */
    private $codigoPago;

    /** @Serializer\Type ("integer") */
    private $montoPago;

    /** @Serializer\Type ("string") */
    private $codigoAutorizacion;

    /** @Serializer\Type ("integer") */
    private $cuotas;

    /**
     * @return mixed
     */
    public function getCodigoPago()
    {
        return $this->codigoPago;
    }

    /**
     * @param mixed $codigoPago
     * @return SimplePago
     */
    public function setCodigoPago($codigoPago)
    {
        $this->codigoPago = $codigoPago;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMontoPago()
    {
        return $this->montoPago;
    }

    /**
     * @param mixed $montoPago
     * @return SimplePago
     */
    public function setMontoPago($montoPago)
    {
        $this->montoPago = $montoPago;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoAutorizacion()
    {
        return $this->codigoAutorizacion;
    }

    /**
     * @param mixed $codigoAutorizacion
     * @return SimplePago
     */
    public function setCodigoAutorizacion($codigoAutorizacion)
    {
        $this->codigoAutorizacion = $codigoAutorizacion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCuotas()
    {
        return $this->cuotas;
    }

    /**
     * @param mixed $cuotas
     * @return SimplePago
     */
    public function setCuotas($cuotas)
    {
        $this->cuotas = $cuotas;
        return $this;
    }


}
