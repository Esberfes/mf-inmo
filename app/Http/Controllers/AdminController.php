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
        $filter = LocalesController::manage_locales_filter_session(SessionConstants::ADMIN_LOCALES_FILTER);

        return view('admin.admin-locales', LocalesController::get_filtered_locales($filter, $pagina, $this->por_pagina));
    }

    public function locales_search()
    {
        $data = request()->all();

        $filter = LocalesController::manage_locales_filter(SessionConstants::ADMIN_LOCALES_FILTER, $data);

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
        $data = request()->validate([
            'titulo' => 'required|unique:locales,titulo',
            'telefono' => 'required',
            'precio' => 'required',
            'metros' => 'required',
            'sector' => 'required',
            'poblacion' => 'required',
            'extracto' => 'required',
            'descripcion' => 'required'
		],[
            'titulo.required' => 'El valor titulo es obligatorio.',
            'titulo.unique' => 'El valor titulo ya existe en la base de datos.',
            'telefono.required' => 'El valor teléfono es obligatorio.',
            'precio.required' => 'El valor precio es obligatorio.',
            'metros.required' => 'El valor metros es obligatorio.',
            'sector.required' => 'El valor sector es obligatorio.',
            'poblacion.required' => 'El valor poblacion es obligatorio.',
            'extracto.required' => 'El valor extracto es obligatorio.',
            'descripcion.required' => 'El valor descripcion es obligatorio.'
        ]);

        $local_url = Local::where('url_amigable' , '=' , Str::slug($data['titulo']))->first();

        if(!empty($local_url))
        {
            return redirect()->back()->withErrors('Se ha intentado generar una url duplicada, pruebe con otro titulo')->withInput();
        }

        $local = Local::create([
            'titulo' => $data['titulo'],
            'url_amigable' => Str::slug($data['titulo']),
            'telefono' => $data['telefono'],
            'precio' => $data['precio'],
            'metros' => $data['metros'],
            'relevante' => 0,
            'extracto' => $data['extracto'],
            'descripcion' => $data['descripcion'],
            'id_sector' => $data['sector'],
            'id_poblacion' => $data['poblacion']
        ]);

        return redirect()->route('locales.editar', ['id' => $local->id])->with('success', 'Local creado con éxito, puede continuar editando.');
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
        $sector = Sector::find($id);
        $now = Carbon::now(new \DateTimeZone('Europe/Madrid'));

        if(empty($sector))
		{
			return view('404');
        }

        $data = request()->validate([
            'titulo' => 'required|unique:sectores,titulo,'.$id,
            'descripcion' => ''
		],[
            'titulo.required' => 'El valor titulo es obligatorio.',
            'titulo.unique' => 'El valor titulo ya existe en la base de datos.',
        ]);

        $sector->titulo = $data['titulo'];
        $sector->descripcion = $data['descripcion'];
        $sector->actualizado_en = $now;

        $sector->save();

        return redirect()->back()->with('success', 'Sector modificado con éxito');
    }

    public function sectores_crear()
    {
        return view('admin.admin-crear-sector');
    }

    public function sectores_crear_nuevo()
    {
        $data = request()->validate([
            'titulo' => 'required|unique:locales,titulo',
            'descripcion' => ''
		],[
            'titulo.required' => 'El valor titulo es obligatorio.',
            'titulo.unique' => 'El valor titulo ya existe en la base de datos.'
        ]);

        $sector = Sector::create([
            'titulo' => $data['titulo'],
            'orden' => 0,
            'descripcion' => $data['descripcion'],
        ]);

        return redirect()->route('sectores.editar', ['id' => $sector->id])->with('success', 'Sector creado con éxito, puede continuar editando.');
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
            'titulo' => 'required|unique:locales,titulo,'.$id,
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

        $local_url = Local::where('url_amigable' , '=' , Str::slug($data['titulo']))->first();

        if(!empty($local_url))
        {
            return redirect()->back()->withErrors('Se ha intentado generar una url duplicada, pruebe con otro titulo')->withInput();
        }

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
}
