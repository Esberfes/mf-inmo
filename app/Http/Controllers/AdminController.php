<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Http\Popos\User;
use App\Http\Popos\LocalFilter;

use App\Http\Controllers\ImageController;

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
	private $max_paginacion = 5;

    public function locales($pagina = null)
    {
        $filter = $this->manage_local_filter_session();

        $query_locales = Local::take($this->por_pagina);

        if($filter->poblacion)
        {
            $query_locales->where("id_poblacion", $filter->poblacion);
        }

        if($filter->sector)
        {
            $query_locales->where("id_sector", $filter->sector);
        }

        if($filter->busqueda)
        {
            $search = $filter->busqueda;
            $query_locales->where(function($query)  use ($search){
				$query->where('titulo','LIKE',"%{$search}%")
				    ->orWhere('extracto','LIKE',"%{$search}%")
				    ->orWhere('descripcion','LIKE',"%{$search}%");
			});
        }

        $order_direction = $filter->order_direction && ($filter->order_direction == 'asc' || $filter->order_direction == 'desc') ? $filter->order_direction : 'desc';

        if(!$filter->order || $filter->order == 'relevancia')
        {
            $query_locales->orderBy("relevante", $order_direction);
        }

        if($filter->order == 'precio')
        {
            $query_locales->orderBy("precio", $order_direction);
        }

        if($filter->order == 'superficie')
        {
            $query_locales->orderBy("metros", $order_direction);
        }

        $paginacion = Paginacion::get($query_locales->count(), $pagina != null ? $pagina : 1, $this->por_pagina);

		if(!$paginacion)
		{
			return view('404');
        }

        $locales = $query_locales->skip($paginacion['offset'])->take($this->por_pagina)->get();

        $sectores = Sector::orderBy('titulo', 'asc')->get();
        $poblaciones = Poblacion::orderBy('nombre', 'asc')->get();

        return view('admin.admin-locales', [
            'locales' => $locales,
            'sectores' => $sectores,
            'poblaciones' => $poblaciones,
            'paginacion' => $paginacion
        ]);
    }

    public function locales_search()
    {
        $filter = $this->manage_local_filter_session();

        $data = request()->all();

        if($data && array_key_exists('action', $data) && $data['action'] == 'order')
        {
            if(array_key_exists('relevancia', $data))
            {
                $filter->order = 'relevancia';
                $filter->order_direction = $data['relevancia'];
            }

            if(array_key_exists('precio', $data))
            {
                $filter->order = 'precio';
                $filter->order_direction = $data['precio'];
            }

            if(array_key_exists('superficie', $data))
            {
                $filter->order = 'superficie';
                $filter->order_direction = $data['superficie'];
            }
        }

        if($data && array_key_exists('action', $data) && $data['action'] == 'search')
        {
            if(array_key_exists('sector', $data))
            {
                if($data['sector'] != 'none')
                {
                    $filter->sector = $data['sector'];
                }
                else
                {
                    $filter->sector = null;
                }
            }

            if(array_key_exists('poblacion', $data))
            {
                if($data['poblacion'] != 'none')
                {
                    $filter->poblacion = $data['poblacion'];
                }
                else
                {
                    $filter->poblacion = null;
                }
            }

            if(array_key_exists('busqueda', $data))
            {
                if(trim($data['busqueda']) && trim($data['busqueda']) != '')
                {
                    $filter->busqueda = trim($data['busqueda']);
                }
                else
                {
                    $filter->busqueda = null;
                }
            }
        }

        Session::put("admin-local-filter", $filter);
        Session::save();

        return $this->locales(null);
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

    public function editar_local($id)
    {
        $local = Local::find($id);

        if(empty($local))
		{
			return view('404');
        }

        $imagen_principal = null;

        foreach($local->medias as $media)
        {
            if($media->tipo == 'principal') {
                $local->imagen_principal = $media;
            }
        }

        $sectores = Sector::orderBy('titulo', 'asc')->get();
        $poblaciones = Poblacion::orderBy('nombre', 'asc')->get();

        return view('admin.admin-editar-local', [
            'local' => $local,
            'sectores' => $sectores,
            'poblaciones' => $poblaciones,
        ]);
    }

    public function editar_local_editar($id)
    {
        $local = Local::find($id);
        $now = Carbon::now(new \DateTimeZone('Europe/Madrid'));

        if(empty($local))
		{
			return view('404');
        }

        $data = request()->validate([
            'titulo' => 'required',
            'telefono' => 'required',
            'precio' => 'required',
            'metros' => 'required',
            'sector' => 'required',
            'poblacion' => 'required',
            'extracto' => 'required',
            'descripcion' => 'required'
		],[
            'titulo.required' => 'El valor titulo es obligatorio.',
            'telefono.required' => 'El valor teléfono es obligatorio.',
            'precio.required' => 'El valor precio es obligatorio.',
            'metros.required' => 'El valor metros es obligatorio.',
            'sector.required' => 'El valor sector es obligatorio.',
            'poblacion.required' => 'El valor poblacion es obligatorio.',
            'extracto.required' => 'El valor extracto es obligatorio.',
            'descripcion.required' => 'El valor descripcion es obligatorio.'
        ]);

        $local->titulo = $data['titulo'];
        $local->url_amigable = Str::slug($data['titulo']);
        $local->telefono = $data['telefono'];
        $local->precio = $data['precio'];
        $local->metros = $data['metros'];
        $local->id_sector = $data['sector'];
        $local->id_poblacion = $data['poblacion'];
        $local->extracto = $data['extracto'];
        $local->descripcion = $data['descripcion'];
        $local->actualizado_en = $now;

        $local->save();

        return redirect()->back()->with('success', 'Local modificado con éxito');
    }

    public function editar_local_crear_caracteristica($id)
    {
        $local = Local::find($id);

        if(empty($local))
		{
			return view('404');
        }

        $data = request()->validate([
			'caracteristica' => 'required'
		],[
			'caracteristica.required' => 'El valor de caracteristica es obligatorio.'
        ]);

        $caracteristica = LocalCaracteristica::create([
            'id_local'=> $id,
            'valor' => $data['caracteristica'],
            'orden' => 0
        ]);

        return redirect()->back()->with('success', 'Caracteristica añadida con éxito');
    }

    public function editar_local_editar_caracteristica($id, $id_caracteristica)
    {
        $local = Local::find($id);
        $now = Carbon::now(new \DateTimeZone('Europe/Madrid'));

        if(empty($local))
		{
			return view('404');
        }

        $data = request()->validate([
            'caracteristica' => 'required',
            'guardar' => '',
            'eliminar' => ''
		],[
			'caracteristica.required' => 'El valor de caracteristica es obligatorio.'
        ]);

        $caracteristica = LocalCaracteristica::find($id_caracteristica);

        if(empty($caracteristica))
		{
			return view('404');
        }

        if(array_key_exists('guardar', $data))
        {
            $caracteristica->valor = $data['caracteristica'];
            $caracteristica->actualizado_en = $now;
            $caracteristica->save();
        }

        if(array_key_exists('eliminar', $data))
        {
            $caracteristica->delete();
        }

        return redirect()->back()->with('success', 'Caracteristica editada con éxito');
    }

    public function editar_local_crear_edificio($id)
    {
        $local = Local::find($id);

        if(empty($local))
		{
			return view('404');
        }

        $data = request()->validate([
			'edificio' => 'required'
		],[
			'edificio.required' => 'El valor de edificio es obligatorio.'
        ]);

        $edificio = LocalEdificio::create([
            'id_local'=> $id,
            'valor' => $data['edificio'],
            'orden' => 0
        ]);

        return redirect()->back()->with('success', 'Edificio añadida con éxito');
    }

    public function editar_local_editar_edificio($id, $id_edificio)
    {
        $local = Local::find($id);
        $now = Carbon::now(new \DateTimeZone('Europe/Madrid'));

        if(empty($local))
		{
			return view('404');
        }

        $data = request()->validate([
            'edificio' => 'required',
            'guardar' => '',
            'eliminar' => ''
		],[
			'edificio.required' => 'El valor de edificio es obligatorio.'
        ]);

        $edificio = LocalEdificio::find($id_edificio);

        if(empty($edificio))
		{
			return view('404');
        }

        if(array_key_exists('guardar', $data))
        {
            $edificio->valor = $data['edificio'];
            $edificio->actualizado_en = $now;
            $edificio->save();
        }

        if(array_key_exists('eliminar', $data))
        {
            $edificio->delete();
        }

        return redirect()->back()->with('success', 'Edificio editada con éxito');
    }

    public function editar_local_crear_equipamiento($id)
    {
        $local = Local::find($id);

        if(empty($local))
		{
			return view('404');
        }

        $data = request()->validate([
			'equipamiento' => 'required'
		],[
			'equipamiento.required' => 'El valor de equipamiento es obligatorio.'
        ]);

        $edificio = LocalEquipamiento::create([
            'id_local'=> $id,
            'valor' => $data['equipamiento'],
            'orden' => 0
        ]);

        return redirect()->back()->with('success', 'Equipamiento añadida con éxito');
    }

    public function editar_local_editar_equipamiento($id, $id_equipamiento)
    {
        $local = Local::find($id);
        $now = Carbon::now(new \DateTimeZone('Europe/Madrid'));

        if(empty($local))
		{
			return view('404');
        }

        $data = request()->validate([
            'equipamiento' => 'required',
            'guardar' => '',
            'eliminar' => ''
		],[
			'equipamiento.required' => 'El valor de equipamiento es obligatorio.'
        ]);

        $equipamiento = LocalEquipamiento::find($id_equipamiento);

        if(empty($equipamiento))
		{
			return view('404');
        }

        if(array_key_exists('guardar', $data))
        {
            $equipamiento->valor = $data['equipamiento'];
            $equipamiento->actualizado_en = $now;

            $equipamiento->save();
        }

        if(array_key_exists('eliminar', $data))
        {
            $equipamiento->delete();
        }

        return redirect()->back()->with('success', 'Equipamiento eliminado con éxito');
    }

    function editar_local_imagen_principal($id)
    {
        $local = Local::find($id);
        $now = Carbon::now(new \DateTimeZone('Europe/Madrid'));

        if(empty($local))
		{
			return view('404');
        }

        $data = request()->validate([
            'imagen_principal' => 'required'
		],[
			'imagen_principal.required' => 'La imagen es obligatoria.'
        ]);

        $found = false;
        $path = ImageController::local_pricipal(request()->file('imagen_principal'));

        foreach($local->medias as $media)
        {
            if($media->tipo == 'principal')
            {
                $media->ruta = $path;
                $media->actualizado_en = $now;
                $media->save();
                $found = true;
                break;
            }
        }

        if(!$found)
        {
            $media = LocalMedia::create([
                'id_local'=> $local->id,
                'ruta' => $path,
                'tipo' => 'principal',
                'orden' => 1
            ]);
        }

        return redirect()->back()->with('success', 'Imagen principal añadida con éxito');
    }

    public function manage_local_filter_session()
    {
        $filter = null;

        if (!Session::exists('admin-local-filter')) {
            $filter = new LocalFilter(Session::getId(), null, null, 'relevante', 'desc', null);
            Session::put("admin-local-filter", $filter);
            Session::save();
        }
        else
        {
            $filter = Session::get('admin-local-filter');
        }

        return $filter;
    }
}
