<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Notifications\Push;
use App\Guest;
use Notification;

use App\Constants\SessionConstants;
use App\Models\Usuario;
use Illuminate\Support\Facades\Session;

class PushController extends BaseController

{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(){

      }

      /**
       * Store the PushSubscription.
       *
       * @param \Illuminate\Http\Request $request
       * @return \Illuminate\Http\JsonResponse
       */
      public function store(){

        $data = request()->validate([
            'endpoint'    => 'required',
            'auth'   => 'required',
            'key' => 'required',
            'encoding' => 'required'
        ]);

        $endpoint = $data['endpoint'];
        $token = $data['auth'];
        $key = $data['key'];
        $contentEncoding =  $data['encoding'];

        $user = Guest::firstOrCreate([
            'endpoint' => $endpoint
        ]);

        $user->updatePushSubscription($endpoint, $key, $token, $contentEncoding);
        $admin = Session::get(SessionConstants::ADMIN_USER);
        if($admin != null)
            $user->id_user = $admin->id;

        $user->ip = request()->ip();
        $user->save();

        return response()->json(['success' => true],200);
      }

      public function push(){
        $guest = Guest::all();
        Notification::send($guest, new Push("Test push", "Prueba de notificación push", $guest->ip));

        return redirect()->back();
    }
}
