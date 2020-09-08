<?php

namespace App\Http\Controllers;


use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;
use App\Constants\SessionConstants;
use App\Models\Usuario;

class LoginController extends BaseController
{

    public function login()
    {
        $data = request()->validate([
            'email' => 'required',
            'pass' => 'required',
        ], [
            'email.required' => 'El campo email es obligatorio.',
            'pass.required' => 'El campo consraseña es obligatorio.'
        ]);

        $usuario = Usuario::where("email", "=", $data['email'])->where("pass", "=", md5(env('APP_KEY') . $data['pass']))->first();

        if (empty($usuario)) {
            return redirect()->back()->withErrors('Email o constraseña invalidos')->withInput();
        }

        $now = Carbon::now(new \DateTimeZone('Europe/Madrid'));
        $usuario->ultimo_login = $now;
        $usuario->save();

        Session::put(SessionConstants::ADMIN_USER, $usuario);
        Session::save();

        return redirect()->route('locales');
    }

    public function login_view()
    {
        return view('admin.admin-login');
    }

    public function logout()
    {
        Session::forget(SessionConstants::ADMIN_USER);
        return redirect()->route('home');
    }

    public static function check()
    {
        return Session::exists(SessionConstants::ADMIN_USER);
    }
}
