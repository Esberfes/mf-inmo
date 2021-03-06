<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Popos\SolicitudFilter;
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
        $query_solicitudes = Solicitud::select('solicitudes.*');

        if ($filter->busqueda) {
            $search = $filter->busqueda;
            $query_solicitudes->where(function ($query)  use ($search) {
                $query->where('nombre', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('solicitudes.telefono', 'LIKE', "%{$search}%");
            });
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

        $solicitudes = $query_solicitudes->skip($paginacion['offset'])->orderBy('creado_en', 'desc')->take($max_per_page)->get();


        return [
            'solicitudes' => $solicitudes,
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
        ], [
            'nombre.required' => 'El nombre de usuario es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email tiene un formato incorrecto.',
            'telefono.required' => 'El telefono es un campo obligatorio'
        ]);

        Solicitud::create([
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


}
