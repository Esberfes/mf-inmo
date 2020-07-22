<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Http\Popos\SolicitudFilter;

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

class SolicitudesController extends BaseController
{
    public static function manage_filter_session($key)
    {
        $filter = null;

        if (!Session::exists($key)) {
            $filter = new SolicitudFilter(Session::getId(), null);
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
        $query_solicitudes = Solicitud::take($max_per_page);

        if($filter->busqueda)
        {
            $search = $filter->busqueda;
            $query_solicitudes->select('locales_datos_solicitudes.*','locales.id_sector', 'locales.titulo', 'sectores.titulo')
            ->leftJoin('locales', 'locales.id', '=', 'id_local')
            ->leftJoin('sectores', 'locales.id_sector', '=', 'sectores.id')
            ->where(function($query)  use ($search){
				$query->where('nombre','LIKE',"%{$search}%")
                    ->orWhere('locales.titulo','LIKE',"%{$search}%")
                    ->orWhere('email','LIKE',"%{$search}%")
                    ->orWhere('sectores.titulo','LIKE',"%{$search}%")
                    ->orWhere('locales_datos_solicitudes.telefono','LIKE',"%{$search}%");
            });

           // dd($query_solicitudes->toSql());
        }

        $paginacion = Paginacion::get($query_solicitudes->count(), $page != null ? $page : 1, $max_per_page);

		if(!$paginacion)
		{
			return view('404');
        }

        $solicitudes = $query_solicitudes->skip($paginacion['offset'])->take($max_per_page)->get();

        return [
            'solicitudes' => $solicitudes,
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
			'nombre' => 'required',
			'email' => ['required' , 'email'],
			'telefono' => 'required',
			'comentario' => '',
			'id_local' => 'required'
		],[
			'nombre.required' => 'El nombre de usuario es obligatorio.',
			'email.required' => 'El email es obligatorio.',
			'email.email' => 'El email tiene un formato incorrecto.',
			'telefono.required' => 'El telefono es un campo obligatorio'
        ]);

        $local = Local::find($data['id_local']);

		if(empty($local))
		{
			return view('404');
        }

        Solicitud::create([
			'id_local' => $data['id_local'],
			'nombre' => $data['nombre'],
			'email' => $data['email'],
			'telefono' => $data['telefono'],
			'comentario' => $data['comentario']
        ]);

        SendEmail::dispatch([
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'local' => $local
        ]);
    }

    public static function update($id_sector, $request)
    {

    }

    public static function delete($id_sector)
    {

    }
}
