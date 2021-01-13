<?php


namespace Lanix;

use JMS\Serializer\Annotation as Serializer;
class SubFamilia extends AbstractGenerica
{
    /** @Serializer\Type ("string") */
    public $codigo;
}
