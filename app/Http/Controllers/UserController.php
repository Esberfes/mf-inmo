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

use App\Helpers\Paginacion;
use Illuminate\Support\Facades\Session;

class UserController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $por_pagina = 4;
	private $max_paginacion = 5;

    public function home()
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

        if(!$user->order || $user->order == 'relevancia')
        {
            $query_locales->orderBy("relevante", 'desc');
        }

        if($user->order == 'barato')
        {
            $query_locales->orderBy("precio", 'asc');
        }

        if($user->order == 'reciente')
        {
            $query_locales->orderBy("creado_en", 'desc');
        }

        $paginacion = Paginacion::get($query_locales->count(), 0);

		if(!$paginacion)
		{
			return view('404');
		}

        $locales = $query_locales->get();

        foreach($locales as $local)
        {
            foreach($local->medias as $media)
            {
                if($media->tipo == 'principal') {
                    $local->imagen_principal = $media;
                }
            }
        }

        $sectores = Sector::orderBy('titulo', 'asc')->get();
        $poblaciones = Poblacion::orderBy('nombre', 'asc')->get();

        return view('home', [
            'locales' => $locales,
            'sectores' => $sectores,
            'poblaciones' => $poblaciones,
            'paginacion' => $paginacion
        ]);
    }

    public function directorio($pagina)
	{
		$paginacion = Paginacion::get(Local::count(), $pagina, $this->por_pagina);

		if($paginacion == false)
		{
			return view('404');
		}

        $locales = Local::skip($paginacion['offset'])
            ->take($this->por_pagina)->get();

        foreach($locales as $local)
        {
            foreach($local->medias as $media)
            {
                if($media->tipo == 'principal') {
                    $local->imagen_principal = $media;
                }
            }
        }

        $user = $this->manage_user_session();

        return view('home', [
            'user' => $user,
            'locales' => $locales,
            'paginacion' => $paginacion
        ]);
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

		return view('local' , [
			"local" => $local[0],
            "user" => $user
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
            }

            if(array_key_exists('barato', $data))
            {
                $user->order = 'barato';
            }

            if(array_key_exists('reciente', $data))
            {
                $user->order = 'reciente';
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
            $user = new User(Session::getId(), null, null, null, null);
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
