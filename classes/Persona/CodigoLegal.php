<?php

namespace Lanix;
use JMS\Serializer\Annotation as Serializer;

class CodigoLegal {

    /**
     * @Serializer\XmlAttribute
     * @Serializer\Type ("string")
     */
    public $digver;

    /**
     * @Serializer\XmlValue
     * @Serializer\Type ("string")
     */
    public $value;

    public function __construct($codlegal, $dv) {
        $this->digver = $dv;
        $this->value = $codlegal;
    }

}
