<?php

namespace App\Http\Popos;

class LocalesSolicitudFilter
{
    public $id;
    public $busqueda;

    /**
     * -1 = todas
     *  0 = atendidas
     *  1 = sin atender
     */
    public $mostrar_atendidos;

    public $sector;

    public function __construct($id, $busqueda)
    {
        $this->id = $id;
        $this->busqueda = $busqueda;
        $this->mostrar_atendidos = -1;
        $this->sector = null;
    }
}
