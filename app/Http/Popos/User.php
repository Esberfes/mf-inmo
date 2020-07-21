<?php

namespace App\Http\Popos;

class User
{
    public $id;
    public $poblacion;
    public $sector;
    public $order;
    public $busqueda;

    public function __construct($id, $poblacion, $sector, $order, $busqueda)
    {
        $this->id = $id;
        $this->poblacion = $poblacion;
        $this->sector = $sector;
        $this->order = $order;
        $this->busqueda = $busqueda;
    }
}
