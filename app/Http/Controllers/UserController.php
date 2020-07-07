<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Http\Popos\User;

use App\Models\Usuario;
use App\Models\Local;
use App\Helpers\Paginacion;

class UserController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $por_pagina = 4;
	private $max_paginacion = 5;

    public function home()
    {
        $paginacion = Paginacion::get(Local::count(), 0);

		if($paginacion == false)
		{
			return view('404');
		}

        $locales =  Local::take($this->por_pagina)->get();

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

        return redirect('/')->with('user', $user);
    }

    public function manage_user_session()
    {
        $user = null;

        if (!session()->has('user')) {
            $user = new User(random_int(100, 999), "Javier", null);
            session()->put("user", $user);
        }
        else
        {
            $user = session()->get('user');
        }

        return $user;
    }
}
