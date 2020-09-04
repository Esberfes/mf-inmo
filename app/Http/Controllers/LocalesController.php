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

class LocalesController extends BaseController
{
    public static function manage_filter_session($key)
    {
        $filter = null;

        if (!Session::exists($key)) {
            $filter = new LocalFilter(Session::getId(), null, null, 'relevancia', 'desc', null, null);
            Session::put($key, $filter);
            Session::save();
        }
        else
        {
            $filter = Session::get($key);
        }

        return $filter;
    }

    public static function get_filtered($filter, $page, $max_per_page)
    {
        $query_locales = Local::take($max_per_page);

        if($filter->poblacion)
        {
            $query_locales->where("id_poblacion", $filter->poblacion);
        }

        if($filter->sector)
        {
            $query_locales->where("id_sector", $filter->sector);
        }

        if($filter->precio)
        {
            $query_locales->where("precio", '<=', $filter->precio);
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

        $paginacion = Paginacion::get($query_locales->count(), $page != null ? $page : 1, $max_per_page);

		if(!$paginacion)
		{
			return view('404');
        }

        $locales = $query_locales->skip($paginacion['offset'])->take($max_per_page)->get();

        foreach($locales as $local)
        {
            foreach($local->medias as $media)
            {
                if($media->tipo == 'principal')
                {
                    $local->imagen_principal = $media;
                }
            }
        }

        $sectores = Sector::orderBy('titulo', 'asc')->get();
        $poblaciones = Poblacion::orderBy('nombre', 'asc')->get();

        return [
            'locales' => $locales,
            'sectores' => $sectores,
            'poblaciones' => $poblaciones,
            'paginacion' => $paginacion
        ];
    }

    public static function manage_filter($session_key, $data)
    {
        $filter = self::manage_filter_session($session_key);

        if($data && array_key_exists('actionOrder', $data))
        {
            if(array_key_exists('relevancia', $data))
            {
                $filter->order = 'relevancia';
                $filter->order_direction = $data['relevancia'];
            }

            if(array_key_exists('precioOrder', $data))
            {
                $filter->order = 'precio';
                $filter->order_direction = $data['precioOrder'];
            }

            if(array_key_exists('superficie', $data))
            {
                $filter->order = 'superficie';
                $filter->order_direction = $data['superficie'];
            }
        }

        if($data && array_key_exists('actionSearch', $data))
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

            if(array_key_exists('precio', $data))
            {
                if($data['precio'] != 'none')
                {
                    $filter->precio = $data['precio'];
                }
                else
                {
                    $filter->precio = null;
                }
            }
        }

        Session::put($session_key, $filter);
        Session::save();
    }

    public static function update($id, $request)
    {
        $local = Local::find($id);
        $now = Carbon::now(new \DateTimeZone('Europe/Madrid'));

        if(empty($local))
		{
			return view('404');
        }

        $data = $request->validate([
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

        $local_url = Local::where('url_amigable' , '=' , Str::slug($data['titulo']))->where('id', '!=', $id)->first();

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

        return $local;
    }

    public static function create($request)
    {
        $data = $request->validate([
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

        return $local;
    }

    public static function delete($id_local)
    {
        $local = Local::find($id_local);

        if(empty($local))
		{
			return view('404');
        }

        $local->delete();
    }

    public static function create_caracteristica($id_local, $request)
    {
        $local = Local::find($id_local);

        if(empty($local))
		{
			return view('404');
        }

        $data = $request->validate([
			'caracteristica' => 'required'
		],[
			'caracteristica.required' => 'El valor de caracteristica es obligatorio.'
        ]);

        $caracteristica = LocalCaracteristica::create([
            'id_local'=> $id_local,
            'valor' => $data['caracteristica'],
            'orden' => 0
        ]);

        return $caracteristica;
    }

    public static function update_caracteristica($id_local, $id_caracteristica, $request)
    {
        $local = Local::find($id_local);
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

        if(array_key_exists('eliminar', $data))
        {
            $caracteristica->delete();

            return null;
        }

        $caracteristica->valor = $data['caracteristica'];
        $caracteristica->actualizado_en = $now;
        $caracteristica->save();

        return $caracteristica;
    }

    public static function create_edificio($id_local, $request)
    {
        $local = Local::find($id_local);

        if(empty($local))
		{
			return view('404');
        }

        $data = $request->validate([
			'edificio' => 'required'
		],[
			'edificio.required' => 'El valor de edificio es obligatorio.'
        ]);

        $edificio = LocalEdificio::create([
            'id_local'=> $id_local,
            'valor' => $data['edificio'],
            'orden' => 0
        ]);

        return $edificio;
    }

    public static function update_edificio($id_local, $id_edificio, $request)
    {
        $local = Local::find($id_local);
        $now = Carbon::now(new \DateTimeZone('Europe/Madrid'));

        if(empty($local))
		{
			return view('404');
        }

        $data = $request->validate([
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

        if(array_key_exists('eliminar', $data))
        {
            $edificio->delete();

            return null;
        }

        $edificio->valor = $data['edificio'];
        $edificio->actualizado_en = $now;
        $edificio->save();

        return $edificio;
    }

    public static function create_equipamiento($id_local, $request)
    {
        $local = Local::find($id_local);

        if(empty($local))
		{
			return view('404');
        }

        $data = $request->validate([
			'equipamiento' => 'required'
		],[
			'equipamiento.required' => 'El valor de equipamiento es obligatorio.'
        ]);

        $equipamiento = LocalEquipamiento::create([
            'id_local'=> $id_local,
            'valor' => $data['equipamiento'],
            'orden' => 0
        ]);

        return $equipamiento;
    }

    public static function update_equipamiento($id_local, $id_equipamiento, $request)
    {
        $local = Local::find($id_local);
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

        if(array_key_exists('eliminar', $data))
        {
            $equipamiento->delete();

            return null;
        }

        $equipamiento->valor = $data['equipamiento'];
        $equipamiento->actualizado_en = $now;

        $equipamiento->save();

        return $equipamiento;
    }

    public static function update_imagen_principal($id_local, $request)
    {
        $local = Local::find($id_local);
        $now = Carbon::now(new \DateTimeZone('Europe/Madrid'));

        if(empty($local))
		{
			return view('404');
        }

        $data = $request->validate([
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
    }
}
