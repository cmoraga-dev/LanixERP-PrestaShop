<?php


namespace Lanix;

use JMS\Serializer\Annotation as Serializer;
class SimpleDocumento
{
    /** @Serializer\Type ("string") */
    private $tipoTotal;

    /** @Serializer\Type ("string") */
    private $folio;

    /** @Serializer\Type ("string") */
    private $tipo;

    /** @Serializer\Type ("string") */
    private $fecha;

    /** @Serializer\Type ("string") */
    private $vencimiento;

    /** @Serializer\Type ("string") */
    private $rutCliente;

    /** @Serializer\Type ("string") */
    private $local;

    /** @Serializer\Type ("string") */
    private $terminal;

    /** @Serializer\Type ("string") */
    private $vendedor;

    /** @Serializer\Type ("string") */
    private $comentario;

    /**
    * @Serializer\XmlList(inline=true, entry="productos")
    * @Serializer\Type("array<Lanix\SimpleProducto>")
    */
    private $productos = [];

    /**
     * @Serializer\XmlList(inline=true, entry="pagos")
     * @Serializer\Type("array<Lanix\SimplePago>")
     */
    private $pagos = [];

    /**
     * @return array
     */
    public function getProductos(): array
    {
        return $this->productos;
    }

    /**
     * @param array $productos
     */
    public function setProductos(array $productos)
    {
        $this->productos = $productos;
        return $this;
    }

    /**
     * @return array
     */
    public function getPagos(): array
    {
        return $this->pagos;
    }

    /**
     * @param array $pagos
     */
    public function setPagos(array $pagos)
    {
        $this->pagos = $pagos;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVencimiento()
    {
        return $this->vencimiento;
    }

    /**
     * @param mixed $vencimiento
     */
    public function setVencimiento($vencimiento)
    {
        $this->vencimiento = $vencimiento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRutCliente()
    {
        return $this->rutCliente;
    }

    /**
     * @param mixed $rutCliente
     */
    public function setRutCliente($rutCliente)
    {
        $this->rutCliente = $rutCliente;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * @param mixed $local
     */
    public function setLocal($local)
    {
        $this->local = $local;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTerminal()
    {
        return $this->terminal;

    }

    /**
     * @param mixed $terminal
     */
    public function setTerminal($terminal)
    {
        $this->terminal = $terminal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVendedor()
    {
        return $this->vendedor;
    }

    /**
     * @param mixed $vendedor
     */
    public function setVendedor($vendedor)
    {
        $this->vendedor = $vendedor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * @param mixed $comentario
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipoTotal()
    {
        return $this->tipoTotal;
    }

    /**
     * @param mixed $tipoTotal
     * @return SimpleDocumento
     */
    public function setTipoTotal($tipoTotal)
    {
        $this->tipoTotal = $tipoTotal;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFolio()
    {
        return $this->folio;
    }

    /**
     * @param mixed $folio
     * @return SimpleDocumento
     */
    public function setFolio($folio)
    {
        $this->folio = $folio;
        return $this;
    }


}
