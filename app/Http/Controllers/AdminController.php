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
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuariosController;

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
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        $filter = LocalesController::manage_filter_session(SessionConstants::ADMIN_LOCALES_FILTER);

        return view('admin.admin-locales', LocalesController::get_filtered($filter, $pagina, $this->por_pagina));
    }

    public function locales_search()
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        $data = request()->all();

        $filter = LocalesController::manage_filter(SessionConstants::ADMIN_LOCALES_FILTER, $data);

        return $this->locales(null);
    }

    public function locales_crear()
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        $sectores = Sector::orderBy('titulo', 'asc')->get();
        $poblaciones = Poblacion::orderBy('nombre', 'asc')->get();

        return view('admin.admin-crear-local', [
            'sectores' => $sectores,
            'poblaciones' => $poblaciones,
        ]);
    }

    public function locales_crear_nuevo()
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        $local = LocalesController::create(request());

        return redirect()->route('locales.editar', ['id' => $local->id])->with('success', 'Local creado con éxito, puede continuar editando.');
    }

    public function editar_local($id)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

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
            }
            elseif($media->tipo == 'banner')
            {
                $local->banner = $media;
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
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        LocalesController::update($id, request());

        return redirect()->back()->with('success', 'Local modificado con éxito');
    }

    public function editar_local_crear_caracteristica($id)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        LocalesController::create_caracteristica($id, request());

        return redirect()->back()->with('success', 'Caracteristica añadida con éxito');
    }

    public function editar_local_editar_caracteristica($id, $id_caracteristica)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        LocalesController::update_caracteristica($id, $id_caracteristica, request());

        return redirect()->back()->with('success', 'Caracteristica editada con éxito');
    }

    public function editar_local_crear_edificio($id)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        LocalesController::create_edificio($id, request());

        return redirect()->back()->with('success', 'Edificio añadida con éxito');
    }

    public function editar_local_editar_edificio($id, $id_edificio)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        LocalesController::update_edificio($id, $id_edificio, request());

        return redirect()->back()->with('success', 'Edificio editada con éxito');
    }

    public function editar_local_crear_equipamiento($id)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        LocalesController::create_equipamiento($id, request());

        return redirect()->back()->with('success', 'Equipamiento añadida con éxito');
    }

    public function editar_local_editar_equipamiento($id, $id_equipamiento)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        LocalesController::update_equipamiento($id, $id_equipamiento, request());

        return redirect()->back()->with('success', 'Equipamiento eliminado con éxito');
    }

    function editar_local_imagen_principal($id)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        LocalesController::update_imagen_principal($id, request());

        return redirect()->back()->with('success', 'Imagen principal añadida con éxito');
    }

    function editar_local_imagen_banner($id)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        LocalesController::update_imagen_banner($id, request());

        return redirect()->back()->with('success', 'Imagen banner añadida con éxito');
    }

    public function sectores($pagina = null)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        $filter = SectoresController::manage_filter_session(SessionConstants::ADMIN_SECTORES_FILTER);

        return view('admin.admin-sectores', SectoresController::get_filtered($filter, $pagina, $this->por_pagina));
    }

    public function sectores_search()
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        $data = request()->all();

        $filter = SectoresController::manage_filter(SessionConstants::ADMIN_SECTORES_FILTER, $data);

        return $this->sectores(null);
    }

    public function editar_sector($id)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

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
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        $sector = SectoresController::update($id, request());

        return redirect()->back()->with('success', 'Sector modificado con éxito');
    }

    public function sectores_crear()
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        return view('admin.admin-crear-sector');
    }

    public function sectores_crear_nuevo()
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        $sector = SectoresController::create(request());

        return redirect()->route('sectores.editar', ['id' => $sector->id])->with('success', 'Sector creado con éxito, puede continuar editando.');
    }

    public function poblaciones($pagina = null)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        $filter = PoblacionesController::manage_filter_session(SessionConstants::ADMIN_POBLACIONES_FILTER);

        return view('admin.admin-poblaciones', PoblacionesController::get_filtered($filter, $pagina, $this->por_pagina));
    }

    public function poblaciones_search()
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        $data = request()->all();

        $filter = PoblacionesController::manage_filter(SessionConstants::ADMIN_POBLACIONES_FILTER, $data);

        return $this->poblaciones(null);
    }

    public function editar_poblacion($id)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

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
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        PoblacionesController::update($id, request());

        return redirect()->back()->with('success', 'Poblacion modificado con éxito');
    }

    public function poblaciones_crear()
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        return view('admin.admin-crear-poblacion');
    }

    public function poblaciones_crear_nuevo()
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        $poblacion = PoblacionesController::create(request());

        return redirect()->route('poblaciones.editar', ['id' => $poblacion->id])->with('success', 'Población creado con éxito, puede continuar editando.');
    }

    public function solicitudes($pagina = null)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        $filter = SolicitudesController::manage_filter_session(SessionConstants::ADMIN_SOLICITUDES_FILTER);

        return view('admin.admin-solicitudes', SolicitudesController::get_filtered($filter, $pagina, $this->por_pagina));
    }

    public function solicitudes_search()
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        $data = request()->all();

        $filter = SolicitudesController::manage_filter(SessionConstants::ADMIN_SOLICITUDES_FILTER, $data);

        return $this->solicitudes(null);
    }

    public function solicitudes_atender($id_solicitud)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        SolicitudesController::update($id_solicitud);

        return redirect()->back()->with('success', 'Solicitud atendida con éxito');
    }

    public function usuarios($pagina = null)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        $filter = UsuariosController::manage_filter_session(SessionConstants::ADMIN_USUARIOS_FILTER);

        return view('admin.admin-usuarios', UsuariosController::get_filtered($filter, $pagina, $this->por_pagina));
    }

    public function usuarios_search()
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        $data = request()->all();

        $filter = UsuariosController::manage_filter(SessionConstants::ADMIN_USUARIOS_FILTER, $data);

        return $this->usuarios(null);
    }

    public function editar_usuario($id)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        $usuario = Usuario::find($id);

        if(empty($usuario))
		{
			return view('404');
        }

        return view('admin.admin-editar-usuario', [
            'usuario' => $usuario
        ]);
    }

    public function editar_usuario_editar($id)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        UsuariosController::update($id, request());

        return redirect()->back()->with('success', 'Usuario modificado con éxito');
    }

    public function usuarios_crear()
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        return view('admin.admin-crear-usuario');
    }

    public function usuarios_crear_nuevo()
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        $usuario = UsuariosController::create(request());

        return redirect()->route('usuarios.editar', ['id' => $usuario->id])->with('success', 'Usuario creado con éxito, puede continuar editando.');
    }

    public function eliminar_usuario($id)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        UsuariosController::delete($id);

        return redirect()->back()->with('success', 'Usuario eliminado con éxito');
    }

    public function eliminar_local($id)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        LocalesController::delete($id);

        return redirect()->back()->with('success', 'Local eliminado con éxito');
    }

    public function eliminar_sector($id)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        $result = SectoresController::delete($id);

        if($result != null)
        {
            return $result;
        }

        return redirect()->back()->with('success', 'Sector eliminado con éxito');
    }

    public function eliminar_poblacion($id)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        $result = PoblacionesController::delete($id);

        if($result != null)
        {
            return $result;
        }

        return redirect()->back()->with('success', 'Población eliminada con éxito');
    }

    public function editar_local_relevante($id)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        $data = request()->all();

        $result = LocalesController::update_relevante($id, $data['checked'] ? 1 : 0);

        if($result == null)
        {
            return response([
                'error'=>true,
                'error-msg'=>"NOT_FOUND " .  $id
            ], 404);
        }

        return response([
            'error'=> false,
            'error-msg'=>"NOT_CONTENT ".$data['checked']
        ], 204);
    }

    public function editar_local_banner($id)
    {
        if(!LoginController::check())
        {
            return redirect()->route('login');
        }

        $data = request()->all();

        $result = LocalesController::update_banner($id, $data['checked'] ? 1 : 0);

        if($result == null)
        {
            return response([
                'error'=>true,
                'error-msg'=>"NOT_FOUND " .  $id
            ], 404);
        }

        return response([
            'error'=> false,
            'error-msg'=>"NOT_CONTENT ".$data['checked']
        ], 204);
    }
}
