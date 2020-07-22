<?php

namespace App\Http\Popos;

class SolicitudFilter
{
    public $id;
    public $busqueda;

    public function __construct($id, $busqueda)
    {
        $this->id = $id;
        $this->busqueda = $busqueda;
    }
}
