<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Popos\SolicitudFilter;
use App\Models\Local;
use App\Models\Sector;
use App\Models\Solicitud;

use App\Helpers\Paginacion;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;

class SolicitudesController extends BaseController
{
    public static function manage_filter_session($key)
    {
        $filter = null;

        if (!Session::exists($key)) {
            $filter = new SolicitudFilter(Session::getId(), null);
            Session::put($key, $filter);
            Session::save();
        } else {
            $filter = Session::get($key);
        }

        return $filter;
    }

    public static function get_filtered($filter, $page, $max_per_page)
    {
        $query_solicitudes = Solicitud::select('locales_datos_solicitudes.*', 'locales.id_sector', 'locales.titulo', 'sectores.titulo')
            ->leftJoin('locales', 'locales.id', '=', 'id_local')
            ->leftJoin('sectores', 'locales.id_sector', '=', 'sectores.id');

        if ($filter->busqueda) {
            $search = $filter->busqueda;
            $query_solicitudes->where(function ($query)  use ($search) {
                $query->where('nombre', 'LIKE', "%{$search}%")
                    ->orWhere('locales.titulo', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('sectores.titulo', 'LIKE', "%{$search}%")
                    ->orWhere('locales_datos_solicitudes.telefono', 'LIKE', "%{$search}%");
            });

            // dd($query_solicitudes->toSql());
        }

        if ($filter->sector) {
            $query_solicitudes->where('locales.id_sector', '=', $filter->sector);
        }

        if ($filter->mostrar_atendidos == 0) {
            $query_solicitudes->whereNotNull("atendido_en");
        }

        if ($filter->mostrar_atendidos == 1) {
            $query_solicitudes->whereNull("atendido_en");
        }

        $paginacion = Paginacion::get($query_solicitudes->count(), $page != null ? $page : 1, $max_per_page);

        if (!$paginacion) {
            return view('404');
        }

        $solicitudes = $query_solicitudes->skip($paginacion['offset'])->take($max_per_page)->get();

        $sectores = Sector::orderBy('titulo', 'asc')->get();

        return [
            'solicitudes' => $solicitudes,
            'sectores' => $sectores,
            'paginacion' => $paginacion
        ];
    }

    public static function manage_filter($session_key, $data)
    {
        $filter = self::manage_filter_session($session_key);

        if (array_key_exists('busqueda', $data)) {
            if (trim($data['busqueda']) && trim($data['busqueda']) != '') {
                $filter->busqueda = trim($data['busqueda']);
            } else {
                $filter->busqueda = null;
            }
        }

        if (array_key_exists('mostrar_atendidos', $data)) {
            $filter->mostrar_atendidos = $data['mostrar_atendidos'];
        }

        if (array_key_exists('sector', $data)) {
            if ($data['sector'] != 'none') {
                $filter->sector = $data['sector'];
            } else {
                $filter->sector = null;
            }
        }

        Session::put($session_key, $filter);
        Session::save();
    }

    public static function create($request)
    {
        $data = $request->validate([
            'nombre' => 'required',
            'email' => ['required', 'email'],
            'telefono' => 'required',
            'comentario' => '',
            'id_local' => 'required'
        ], [
            'nombre.required' => 'El nombre de usuario es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email tiene un formato incorrecto.',
            'telefono.required' => 'El telefono es un campo obligatorio'
        ]);

        $local = Local::find($data['id_local']);

        if (empty($local)) {
            return view('404');
        }

        Solicitud::create([
            'id_local' => $data['id_local'],
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'telefono' => $data['telefono'],
            'comentario' => $data['comentario']
        ]);
    }

    public static function update($id_solicitud)
    {
        $now = Carbon::now(new \DateTimeZone('Europe/Madrid'));

        $solicitud = Solicitud::find($id_solicitud);

        if (empty($solicitud)) {
            return view('404');
        }

        $solicitud->atendido_en = $now;
        $solicitud->save();
    }

    public static function delete($id_sector)
    {
    }
}
