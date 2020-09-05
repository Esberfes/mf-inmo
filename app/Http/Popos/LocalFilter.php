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
    public $relevante;

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
    }
}
