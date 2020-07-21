<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Http\Popos\User;

use App\Models\Usuario;
use App\Models\Local;
use App\Models\Sector;
use App\Models\Poblacion;
use App\Models\Solicitud;

use App\Jobs\SendEmail;

use App\Helpers\Paginacion;
use Illuminate\Support\Facades\Session;

class AdminController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $por_pagina = 10;
	private $max_paginacion = 5;

    public function locales($pagina = null)
    {
        $query_locales = Local::take($this->por_pagina);

        $paginacion = Paginacion::get($query_locales->count(), $pagina != null ? $pagina : 1, $this->por_pagina);

        $locales = $query_locales->skip($paginacion['offset'])->take($this->por_pagina)->get();

        return view('admin.admin-locales', [
            'locales' => $locales,
            'paginacion' => $paginacion
        ]);
    }

    public function sectores($pagina = null)
    {
        $query_sectores = Sector::take($this->por_pagina);

        $paginacion = Paginacion::get($query_sectores->count(), $pagina != null ? $pagina : 1, $this->por_pagina);

        $sectores = $query_sectores->skip($paginacion['offset'])->take($this->por_pagina)->get();

        return view('admin.admin-sectores', [
            'sectores' => $sectores,
            'paginacion' => $paginacion
        ]);
    }

    public function poblaciones($pagina = null)
    {
        $query_poblaciones = Poblacion::take($this->por_pagina);

        $paginacion = Paginacion::get($query_poblaciones->count(), $pagina != null ? $pagina : 1, $this->por_pagina);

        $poblaciones = $query_poblaciones->skip($paginacion['offset'])->take($this->por_pagina)->get();

        return view('admin.admin-poblaciones', [
            'poblaciones' => $poblaciones,
            'paginacion' => $paginacion
        ]);
    }

    public function solicitudes($pagina = null)
    {
        $query_solicitudes = Solicitud::take($this->por_pagina);

        $paginacion = Paginacion::get($query_solicitudes->count(), $pagina != null ? $pagina : 1, $this->por_pagina);

        $solicitudes = $query_solicitudes->skip($paginacion['offset'])->take($this->por_pagina)->get();

        return view('admin.admin-solicitudes', [
            'solicitudes' => $solicitudes,
            'paginacion' => $paginacion
        ]);
    }

    public function usuarios($pagina = null)
    {
        $query_usuarios = Usuario::take($this->por_pagina);

        $paginacion = Paginacion::get($query_usuarios->count(), $pagina != null ? $pagina : 1, $this->por_pagina);

        $usuarios = $query_usuarios->skip($paginacion['offset'])->take($this->por_pagina)->get();

        return view('admin.admin-usuarios', [
            'usuarios' => $usuarios,
            'paginacion' => $paginacion
        ]);
    }
}
