<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Constants\SessionConstants;
use App\Http\Popos\UserSession;
use App\Models\Local;
use App\Models\Sector;
use App\Models\Poblacion;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;

class UserController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const SESSION_LOCALES_FILTER = 'user-local-filter';

    private $por_pagina = 4;

    public function solicitud()
    {
        $data = request()->validate([
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

        return redirect()->back()->with('success', 'Solicitud enviada con éxito');
    }

    public function home($pagina = null)
    {
        $filter = LocalesController::manage_filter_session(SessionConstants::USER_LOCALES_FILTER);
        $user_session = UserController::manage_user_session(request());

        return view('home', LocalesController::get_filtered($filter, $pagina, $this->por_pagina));
    }

    public static function manage_user_session($request)
    {
        $user_session = null;
        $key = "USER_SESSION";

        if (!Session::exists($key)) {
            $user_session = new UserSession(Session::getId(), $request->ip(), Carbon::now(new \DateTimeZone('Europe/Madrid')));
            UserController::save_user_session($user_session);
        } else {
            $user_session = Session::get($key);
        }

        return $user_session;
    }

    public static function save_user_session($user_session)
    {
        $key = "USER_SESSION";

        Session::put($key, $user_session);
        Session::save();
    }

    public function directorio_local($url)
    {
        $local = Local::where('url_amigable', '=', $url)->where('activo', '=', "1")->get();
        LocalesController::manage_filter_session(SessionConstants::USER_LOCALES_FILTER);

        if (empty($local[0])) {
            return view('404');
        }

        $imagen_principal = null;

        foreach ($local[0]->medias as $media) {
            if ($media->tipo == 'principal') {
                $local[0]->imagen_principal = $media;
            }
        }

        $sectores = Sector::orderBy('titulo', 'asc')->get();
        $poblaciones = Poblacion::orderBy('nombre', 'asc')->get();
        $locales_banner = Local::where('banner_activo', '=', '1')->inRandomOrder()->take(2)->get();

        $banners = [];

        foreach ($locales_banner as $localb) {
            foreach ($localb->medias as $media) {
                if ($media->tipo == 'banner') {
                    $banners[] = $media;
                }
            }
        }

        return view('local', [
            "local" => $local[0],
            'sectores' => $sectores,
            'poblaciones' => $poblaciones,
            'banners' => $banners
        ]);
    }

    public function home_search()
    {
        $data = request()->all();

        $filter = LocalesController::manage_filter(SessionConstants::USER_LOCALES_FILTER, $data);

        return $this->home(null);
    }
}
