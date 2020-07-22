<?php

namespace App\Http\Popos;

class UsuarioFilter
{
    public $id;
    public $busqueda;

    public function __construct($id, $busqueda)
    {
        $this->id = $id;
        $this->busqueda = $busqueda;
    }
}
