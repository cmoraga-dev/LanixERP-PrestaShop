<?php


namespace Lanix;
use JMS\Serializer\Annotation as Serializer;
abstract class AbstractGenerica
{
    /**
     * @Serializer\Type ("boolean")
     */
    protected $sincronizado;

    /**
     * @Serializer\Type ("integer")
     */
    public $codigoTabla;

    /**
     * @Serializer\Type ("integer")
     */
    public $codigo;

    /** @Serializer\Type ("string") */
    public $descripcion;

    public function getSincronizado() {
        return $this->sincronizado;
    }

    public function getCodigoTabla() {
        return $this->codigoTabla;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function __construct ($codTabla = null,$cod = null ,$desc = null){
        $this->sincronizado = true;
        $this->codigoTabla = $codTabla;
        $this->codigo = $cod;
        $this->descripcion = $desc;
    }


    public function setCodigoTabla( $codigoTabla)
    {
        $this->codigoTabla = $codigoTabla;
    }

    public function setCodigo( $codigo)
    {
        $this->codigo = $codigo;
    }

    public function setDescripcion( $descripcion)
    {
        $this->descripcion = $descripcion;
    }
}
