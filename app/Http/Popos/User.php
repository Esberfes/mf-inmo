<?php

namespace App\Http\Popos;

class User
{
    public $id;
    public $name;
    public $poblacion;
    public $sector;
    public $order;
    public $busqueda;

    public function __construct($id, $name, $poblacion, $sector, $order, $busqueda)
    {
        $this->id = $id;
        $this->name = $name;
        $this->poblacion = $poblacion;
        $this->sector = $sector;
        $this->order = $order;
        $this->busqueda = $busqueda;
    }
}
