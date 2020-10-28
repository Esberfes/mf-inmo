<?php

namespace App\Http\Popos;

class LocalFilter
{
    public $id;
    public $poblacion;
    public $sector;
    public $order;
    public $order_direction;
    public $busqueda;
    public $precio;
    public $precio_alquiler;
    public $relevante;
    public $activo;

    /**
     * -1 = mostrar alquiler y compra
     *  0 = mostrar solo compra
     *  1 = mostrar solo alquiler
     */
    public $mostrar_compra_alquiler;

    public function __construct($id, $poblacion, $sector, $order, $order_direction, $busqueda, $precio)
    {
        $this->id = $id;
        $this->poblacion = $poblacion;
        $this->sector = $sector;
        $this->order = $order;
        $this->order_direction = $order_direction;
        $this->busqueda = $busqueda;
        $this->precio = $precio;
        $this->relevante = -1;
        $this->activo = -1;
        $this->precio_alquiler = null;
        $this->mostrar_compra_alquiler = -1;
    }
}
