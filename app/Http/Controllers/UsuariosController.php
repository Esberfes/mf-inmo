<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Http\Popos\User;
use App\Http\Popos\UsuarioFilter;

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

class UsuariosController extends BaseController
{
    public static function manage_filter_session($key)
    {
        $filter = null;

        if (!Session::exists($key)) {
            $filter = new UsuarioFilter(Session::getId(), null);
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
        $query_usuarios = Usuario::take($max_per_page);

        if($filter->busqueda)
        {
            $search = $filter->busqueda;
            $query_usuarios->where(function($query)  use ($search){
                $query->where('nombre','LIKE',"%{$search}%")
                    ->orWhere('email', 'LIKE',"%{$search}%")
                    ->orWhere('telefono', 'LIKE',"%{$search}%");
			});
        }

        $paginacion = Paginacion::get($query_usuarios->count(), $page != null ? $page : 1, $max_per_page);

		if(!$paginacion)
		{
			return view('404');
        }

        $usuarios = $query_usuarios->skip($paginacion['offset'])->take($max_per_page)->get();

        return [
            'usuarios' => $usuarios,
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
            'email' => 'required|unique:usuarios,email',
            'telefono' => 'required',
            'pass' => 'required',
		],[
            'nombre.required' => 'El campo nombre es obligatorio.',
            'email.unique' => 'El valor email ya existe en la base de datos.',
            'email.required' => 'El campo email es obligatorio.',
            'telefono.required' => 'El campo telefono es obligatorio.',
            'pass.required' => 'El campo pass es obligatorio.',
        ]);

        $usuario = Usuario::create([
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'telefono' => $data['telefono'],
            'pass' =>  md5(env('APP_ENV').$data['pass']),
            'rol' => 'administrador'
        ]);

        return $usuario;
    }

    public static function update($id_usuario, $request)
    {
        $usuario = Usuario::find($id_usuario);
        $now = Carbon::now(new \DateTimeZone('Europe/Madrid'));

        if(empty($usuario))
		{
			return view('404');
        }

        $data = $request->validate([
            'nombre' => 'required',
            'email' => 'required|unique:usuarios,email,'.$usuario->id,
            'telefono' => 'required',
            'pass' => '',
		],[
            'nombre.required' => 'El campo titulo es obligatorio.',
            'email.unique' => 'El valor email ya existe en la base de datos.'
        ]);

        $usuario->nombre = $data['nombre'];
        $usuario->email = $data['email'];
        $usuario->telefono = $data['telefono'];
        if(array_key_exists('pass', $data) && !empty($data['pass']))
        {
            $usuario->pass = md5(env('APP_ENV').$data['pass']);
        }
        $usuario->actualizado_en = $now;

        $usuario->save();

        return $usuario;
    }

    public static function delete($id_usuario)
    {
        $usuario = Usuario::find($id_usuario);

        if(empty($usuario))
		{
			return view('404');
        }

        $usuario->delete();
    }
}
