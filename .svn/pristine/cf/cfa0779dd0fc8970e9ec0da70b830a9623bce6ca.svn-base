<?php


namespace Lanix;

use JMS\Serializer\Annotation as Serializer;
/** @Serializer\XmlRoot ("venta") */
class Venta
{
    /** @Serializer\XmlKeyValuePairs
     * @Serializer\Type ("Lanix\SimplePersona")
     */
    private $cliente;

    /** @Serializer\XmlKeyValuePairs
     * @Serializer\Type ("Lanix\SimpleDocumento")
     */
    private $documento;

    /**
     * @return mixed
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * @param mixed $cliente
     */
    public function setCliente($cliente)
    {
        $this->cliente = $cliente;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * @param mixed $documento
     */
    public function setDocumento($documento)
    {
        $this->documento = $documento;
        return $this;
    }


}
