<?php


namespace Lanix;
use JMS\Serializer\Annotation as Serializer;

class Direccion
{
    /**
     * @Serializer\Type ("Lanix\Pais")
     */
    private $pais;

    /**
     * @Serializer\Type ("Lanix\Region")
     */
    private $region;

    /**
     * @Serializer\Type ("Lanix\Ciudad")
     */
    private $ciudad;

    /**
     * @Serializer\Type ("Lanix\Comuna")
     */
    private $comuna;

    /**
     * @Serializer\Type ("string")
     */
    private $calleNumero;

    /**
     * @Serializer\Type ("string")
     */
    private $codPostal;

    /**
     * Direccion constructor.
     * @param $pais
     * @param $region
     * @param $ciudad
     * @param $comuna
     * @param $calleNumero
     * @param $codPostal
     */
    public function __construct($pais = null, $region = null, $ciudad = null, $comuna = null, $calleNumero = null, $codPostal = null)
    {
        $this->pais = $pais;
        $this->region = $region;
        $this->ciudad = $ciudad;
        $this->comuna = $comuna;
        $this->calleNumero = $calleNumero;
        $this->codPostal = $codPostal;
    }

    /**
     * @return mixed
     */
    public function getCalleNumero()
    {
        return $this->calleNumero;
    }

    /**
     * @param mixed $calleNumero
     */
    public function setCalleNumero($calleNumero)
    {
        $this->calleNumero = $calleNumero;
    }

    /**
     * @return mixed
     */
    public function getCodPostal()
    {
        return $this->codPostal;
    }

    /**
     * @param mixed $codPostal
     */
    public function setCodPostal($codPostal)
    {
        $this->codPostal = $codPostal;
    }

    /**
     * @return mixed
     */
    public function getPais()
    {
        return $this->pais;
    }

    /**
     * @param mixed $pais
     */
    public function setPais($pais)
    {
        $this->pais = $pais;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return mixed
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * @param mixed $ciudad
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;
    }

    /**
     * @return mixed
     */
    public function getComuna()
    {
        return $this->comuna;
    }

    /**
     * @param mixed $comuna
     */
    public function setComuna($comuna)
    {
        $this->comuna = $comuna;
    }




}
