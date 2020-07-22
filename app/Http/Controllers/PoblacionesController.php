<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Http\Popos\User;
use App\Http\Popos\PoblacionFilter;

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

class PoblacionesController extends BaseController
{
    public static function manage_filter_session($key)
    {
        $filter = null;

        if (!Session::exists($key)) {
            $filter = new PoblacionFilter(Session::getId(), null);
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
        $query_poblaciones = Poblacion::take($max_per_page);

        if($filter->busqueda)
        {
            $search = $filter->busqueda;
            $query_poblaciones->where(function($query)  use ($search){
				$query->where('nombre','LIKE',"%{$search}%");
			});
        }

        $paginacion = Paginacion::get($query_poblaciones->count(), $page != null ? $page : 1, $max_per_page);

		if(!$paginacion)
		{
			return view('404');
        }

        $poblaciones = $query_poblaciones->skip($paginacion['offset'])->take($max_per_page)->get();

        return [
            'poblaciones' => $poblaciones,
            'paginacion' => $paginacion
        ];
    }

    public static function manage_filter($session_key, $data)
    {
        $filter = self::manage_filter_session($session_key);

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

        Session::put($session_key, $filter);
        Session::save();
    }

    public static function create($request)
    {
        $data = $request->validate([
            'nombre' => 'required|unique:poblaciones,nombre',
		],[
            'nombre.required' => 'El valor titulo es obligatorio.',
            'nombre.unique' => 'El valor titulo ya existe en la base de datos.'
        ]);

        $poblacion = Poblacion::create([
            'nombre' => $data['nombre'],
            'orden' => 0
        ]);

        return $poblacion;
    }

    public static function update($id_poblacion, $request)
    {
        $poblacion = Poblacion::find($id_poblacion);
        $now = Carbon::now(new \DateTimeZone('Europe/Madrid'));

        if(empty($poblacion))
		{
			return view('404');
        }

        $data = $request->validate([
            'nombre' => 'required|unique:poblaciones,nombre,'.$id_poblacion,
		],[
            'nombre.required' => 'El valor nombre es obligatorio.',
            'nombre.unique' => 'El valor nombre ya existe en la base de datos.',
        ]);

        $poblacion->nombre = $data['nombre'];
        $poblacion->actualizado_en = $now;

        $poblacion->save();

        return $poblacion;
    }

    public static function delete($id_poblacion)
    {
        $poblacion = Poblacion::find($id_poblacion);

        if(empty($poblacion))
		{
			return view('404');
        }

        if(!empty($poblacion->locales) && $poblacion->locales->count() > 0)
        {
            return redirect()->back()->withErrors("No se puede eliminar la población, tiene locales asociados.");
        }

        $poblacion->delete();

        return null;
    }
}
