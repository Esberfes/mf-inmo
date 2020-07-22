<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Constants\SessionConstants;
use App\Http\Popos\LocalFilter;

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

    const SESSION_LOCALES_FILTER = 'user-local-filter';

    private $por_pagina = 4;

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

    public function home($pagina = null)
    {
        $filter = LocalesController::manage_locales_filter_session(SessionConstants::USER_LOCALES_FILTER);

        return view('home', LocalesController::get_filtered_locales($filter, $pagina, $this->por_pagina));
    }

    public function directorio_local($url)
    {
        $local = Local::where('url_amigable' , '=' , $url)->get();

		if(empty($local[0]))
		{
			return view('404');
        }

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
        $data = request()->all();

        $filter = LocalesController::manage_locales_filter(SessionConstants::USER_LOCALES_FILTER, $data);

        return $this->home(null);
    }
}
