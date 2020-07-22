<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Constants\SessionConstants;
use App\Http\Popos\LocalFilter;

use App\Http\Controllers\ImageController;
use App\Http\Controllers\LocalesController;
use App\Http\Controllers\SectoresController;
use App\Http\Controllers\PoblacionesController;

use App\Models\Usuario;
use App\Models\Local;
use App\Models\Sector;
use App\Models\Poblacion;
use App\Models\Solicitud;
use App\Models\LocalCaracteristica;
use App\Models\LocalEdificio;
use App\Models\LocalEquipamiento;
use App\Models\LocalMedia;

use App\Jobs\SendEmail;

use App\Helpers\Paginacion;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class AdminController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $por_pagina = 10;

    public function locales($pagina = null)
    {
        $filter = LocalesController::manage_filter_session(SessionConstants::ADMIN_LOCALES_FILTER);

        return view('admin.admin-locales', LocalesController::get_filtered($filter, $pagina, $this->por_pagina));
    }

    public function locales_search()
    {
        $data = request()->all();

        $filter = LocalesController::manage_filter(SessionConstants::ADMIN_LOCALES_FILTER, $data);

        return $this->locales(null);
    }

    public function locales_crear()
    {
        $sectores = Sector::orderBy('titulo', 'asc')->get();
        $poblaciones = Poblacion::orderBy('nombre', 'asc')->get();

        return view('admin.admin-crear-local', [
            'sectores' => $sectores,
            'poblaciones' => $poblaciones,
        ]);
    }

    public function locales_crear_nuevo()
    {
        $local = LocalesController::create(request());

        return redirect()->route('locales.editar', ['id' => $local->id])->with('success', 'Local creado con éxito, puede continuar editando.');
    }

    public function editar_local($id)
    {
        $local = Local::find($id);

        if(empty($local))
		{
			return view('404');
        }

        foreach($local->medias as $media)
        {
            if($media->tipo == 'principal')
            {
                $local->imagen_principal = $media;
                break;
            }
        }

        return view('admin.admin-editar-local', [
            'local' => $local,
            'sectores' => Sector::orderBy('titulo', 'asc')->get(),
            'poblaciones' => Poblacion::orderBy('nombre', 'asc')->get(),
        ]);
    }

    public function editar_local_editar($id)
    {
        LocalesController::update($id, request());

        return redirect()->back()->with('success', 'Local modificado con éxito');
    }

    public function editar_local_crear_caracteristica($id)
    {
        LocalesController::create_caracteristica($id, request());

        return redirect()->back()->with('success', 'Caracteristica añadida con éxito');
    }

    public function editar_local_editar_caracteristica($id, $id_caracteristica)
    {
        LocalesController::update_caracteristica($id, $id_caracteristica, request());

        return redirect()->back()->with('success', 'Caracteristica editada con éxito');
    }

    public function editar_local_crear_edificio($id)
    {
        LocalesController::create_edificio($id, request());

        return redirect()->back()->with('success', 'Edificio añadida con éxito');
    }

    public function editar_local_editar_edificio($id, $id_edificio)
    {
        LocalesController::update_edificio($id, $id_edificio, request());

        return redirect()->back()->with('success', 'Edificio editada con éxito');
    }

    public function editar_local_crear_equipamiento($id)
    {
        LocalesController::create_equipamiento($id, request());

        return redirect()->back()->with('success', 'Equipamiento añadida con éxito');
    }

    public function editar_local_editar_equipamiento($id, $id_equipamiento)
    {
        LocalesController::update_equipamiento($id, $id_equipamiento, request());

        return redirect()->back()->with('success', 'Equipamiento eliminado con éxito');
    }

    function editar_local_imagen_principal($id)
    {
        LocalesController::update_imagen_principal($id, request());

        return redirect()->back()->with('success', 'Imagen principal añadida con éxito');
    }

    public function sectores($pagina = null)
    {
        $filter = SectoresController::manage_filter_session(SessionConstants::ADMIN_SECTORES_FILTER);

        return view('admin.admin-sectores', SectoresController::get_filtered($filter, $pagina, $this->por_pagina));
    }

    public function sectores_search()
    {
        $data = request()->all();

        $filter = SectoresController::manage_filter(SessionConstants::ADMIN_SECTORES_FILTER, $data);

        return $this->sectores(null);
    }

    public function editar_sector($id)
    {
        $sector = Sector::find($id);

        if(empty($sector))
		{
			return view('404');
        }

        return view('admin.admin-editar-sector', [
            'sector' => $sector
        ]);
    }

    public function editar_sector_editar($id)
    {
        $sector = SectoresController::update($id, request());

        return redirect()->back()->with('success', 'Sector modificado con éxito');
    }

    public function sectores_crear()
    {
        return view('admin.admin-crear-sector');
    }

    public function sectores_crear_nuevo()
    {
        $sector = SectoresController::create(request());

        return redirect()->route('sectores.editar', ['id' => $sector->id])->with('success', 'Sector creado con éxito, puede continuar editando.');
    }

    public function poblaciones($pagina = null)
    {
        $filter = PoblacionesController::manage_filter_session(SessionConstants::ADMIN_POBLACIONES_FILTER);

        return view('admin.admin-poblaciones', PoblacionesController::get_filtered($filter, $pagina, $this->por_pagina));
    }

    public function poblaciones_search()
    {
        $data = request()->all();

        $filter = PoblacionesController::manage_filter(SessionConstants::ADMIN_POBLACIONES_FILTER, $data);

        return $this->poblaciones(null);
    }

    public function editar_poblacion($id)
    {
        $poblacion = Poblacion::find($id);

        if(empty($poblacion))
		{
			return view('404');
        }

        return view('admin.admin-editar-poblacion', [
            'poblacion' => $poblacion
        ]);
    }

    public function editar_poblacion_editar($id)
    {
        PoblacionesController::update($id, request());

        return redirect()->back()->with('success', 'Poblacion modificado con éxito');
    }

    public function poblaciones_crear()
    {
        return view('admin.admin-crear-poblacion');
    }

    public function poblaciones_crear_nuevo()
    {
        $poblacion = PoblacionesController::create(request());

        return redirect()->route('poblaciones.editar', ['id' => $poblacion->id])->with('success', 'Población creado con éxito, puede continuar editando.');
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
