<?php

namespace App\Http\Popos;

class SolicitudFilter
{
    public $id;
    public $busqueda;
    public $mostrar_atendidos;

    public function __construct($id, $busqueda)
    {
        $this->id = $id;
        $this->busqueda = $busqueda;
        $this->mostrar_atendidos = -1;
    }
}
