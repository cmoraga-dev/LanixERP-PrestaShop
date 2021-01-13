<?php


namespace Lanix;

use JMS\Serializer\Annotation as Serializer;
class CondicionPago extends AbstractGenerica
{    /**
     * @Serializer\SerializedName ("valoresNetos")
     */
    protected $valoresNetos;

    public function __construct ($codTabla,$cod,$desc,$valoresNetos)
    {
        parent::__construct($codTabla, $cod, $desc);
        $this->valoresNetos = $valoresNetos;
    }
}
