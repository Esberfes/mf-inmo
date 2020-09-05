<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Notifications\Push;
use App\Guest;
use Notification;

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
              'authToken'   => 'required',
              'publicKey' => 'required',
              'contentEncoding' => 'required'
              ]);

          $endpoint = $data['endpoint'];
          $token = $data['authToken'];
          $key = $data['publicKey'];
            $contentEncoding =  $data['contentEncoding'];

          $user = Guest::firstOrCreate([
            'endpoint' => $endpoint
            ]);

          $user->updatePushSubscription($endpoint, $key, $token, $contentEncoding);

          return response()->json(['success' => true],200);
      }

      public function push(){
          $guest = Guest::all();
         Notification::send($guest,new Push());

        return redirect()->back();
    }
}
