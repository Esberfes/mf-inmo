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

use App\Jobs\SendEmail;

use App\Helpers\Paginacion;
use Illuminate\Support\Facades\Session;

class UserController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $por_pagina = 4;
	private $max_paginacion = 5;


    public function solicitud()
    {
        $data = request()->validate([
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

        return redirect()->back()->with('success', 'Solicitud enviada con éxito');
    }

    public function home()
    {
        return view('home', $this->get_local_data(null));
    }

    public function directorio($pagina)
	{
        return view('home', $this->get_local_data($pagina));
    }

    public function get_local_data($pagina)
    {
		$user = $this->manage_user_session();

        $query_locales = Local::take($this->por_pagina);

        if($user->poblacion)
        {
            $query_locales->where("id_poblacion", $user->poblacion);
        }

        if($user->sector)
        {
            $query_locales->where("id_sector", $user->sector);
        }

        if($user->busqueda)
        {
            $search = $user->busqueda;
            $query_locales->where(function($query)  use ($search){
				$query->where('titulo','LIKE',"%{$search}%")
				    ->orWhere('extracto','LIKE',"%{$search}%")
				    ->orWhere('descripcion','LIKE',"%{$search}%");
			});
        }

        $order_direction = $user->order_direction && ($user->order_direction == 'asc' || $user->order_direction == 'desc') ? $user->order_direction : 'desc';

        if(!$user->order || $user->order == 'relevancia')
        {
            $query_locales->orderBy("relevante", $order_direction);
        }

        if($user->order == 'precio')
        {
            $query_locales->orderBy("precio", $order_direction);
        }

        if($user->order == 'superficie')
        {
            $query_locales->orderBy("metros", $order_direction);
        }

        $paginacion = Paginacion::get($query_locales->count(), $pagina != null ? $pagina : 1, $this->por_pagina);

		if(!$paginacion)
		{
			return view('404');
        }

        $locales = $query_locales->skip($paginacion['offset'])->take($this->por_pagina)->get();

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

    public function directorio_local($url)
    {
        $local = Local::where('url_amigable' , '=' , $url)->get();

		if(empty($local[0]))
		{
			return view('404');
        }

        $user = $this->manage_user_session();

        $imagen_principal = null;

        foreach($local[0]->medias as $media)
        {
            if($media->tipo == 'principal') {
                $local[0]->imagen_principal = $media;
            }
        }

        $sectores = Sector::orderBy('titulo', 'asc')->get();
        $poblaciones = Poblacion::orderBy('nombre', 'asc')->get();

		return view('local' , [
			"local" => $local[0],
            'sectores' => $sectores,
            'poblaciones' => $poblaciones
		]);
    }

    public function home_action()
    {
        $user = $this->manage_user_session();

        $data = request()->all();

        if($data && array_key_exists('action', $data) && $data['action'] == 'order')
        {
            if(array_key_exists('relevancia', $data))
            {
                $user->order = 'relevancia';
                $user->order_direction = $data['relevancia'];
            }

            if(array_key_exists('precio', $data))
            {
                $user->order = 'precio';
                $user->order_direction = $data['precio'];
            }

            if(array_key_exists('superficie', $data))
            {
                $user->order = 'superficie';
                $user->order_direction = $data['superficie'];
            }
        }

        if($data && array_key_exists('action', $data) && $data['action'] == 'search')
        {
            if(array_key_exists('sector', $data))
            {
                if($data['sector'] != 'none')
                {
                    $user->sector = $data['sector'];
                }
                else
                {
                    $user->sector = null;
                }
            }

            if(array_key_exists('poblacion', $data))
            {
                if($data['poblacion'] != 'none')
                {
                    $user->poblacion = $data['poblacion'];
                }
                else
                {
                    $user->poblacion = null;
                }
            }

            if(array_key_exists('busqueda', $data))
            {
                if(trim($data['busqueda']) && trim($data['busqueda']) != '')
                {
                    $user->busqueda = trim($data['busqueda']);
                }
                else
                {
                    $user->busqueda = null;
                }
            }
        }

        Session::put("user", $user);
        Session::save();

        return $this->home();
    }

    public function manage_user_session()
    {
        $user = null;

        if (!Session::exists('user')) {
            $user = new User(Session::getId(), null, null, 'relevante', 'desc', null);
            Session::put("user", $user);
            Session::save();
        }
        else
        {
            $user = Session::get('user');
        }

        return $user;
    }
}
