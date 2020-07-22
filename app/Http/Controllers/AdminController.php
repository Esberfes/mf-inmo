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
use App\Models\LocalCaracteristica;
use App\Models\LocalEdificio;
use App\Models\LocalEquipamiento;
use App\Models\LocalMedia;

use App\Jobs\SendEmail;

use App\Helpers\Paginacion;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Carbon;

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

    public function editar_local($id)
    {
        $local = Local::find($id);

        if(empty($local))
		{
			return view('404');
        }

        $sectores = Sector::orderBy('titulo', 'asc')->get();
        $poblaciones = Poblacion::orderBy('nombre', 'asc')->get();

        return view('admin.admin-editar-local', [
            'local' => $local,
            'sectores' => $sectores,
            'poblaciones' => $poblaciones,
        ]);
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
            $equipamiento->save();
        }

        if(array_key_exists('eliminar', $data))
        {
            $equipamiento->delete();
        }

        return redirect()->back()->with('success', 'Equipamiento eliminado con éxito');
    }
}
