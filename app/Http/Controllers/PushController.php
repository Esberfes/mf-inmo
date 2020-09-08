<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Guest;
use App\Constants\SessionConstants;
use Illuminate\Support\Facades\Session;
use App\Events\ServerCheckEvent;
use App\Events\ActivityEvent;
use App\Events\ActivityEventAdmin;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use \Illuminate\Broadcasting\BroadcastException;

class PushController extends BaseController

{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
    }

    /**
     * Store the PushSubscription.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {

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
        if ($admin != null)
            $user->id_user = $admin->id;

        $user->ip = request()->ip();
        $user->save();

        return response()->json(['success' => true], 200);
    }

    /**
     * Websocket
     */
    public function ping_on_activity_channel()
    {
        try {
            if (!LoginController::check()) {
                return response()->json([], 403);
            }

            event(new ActivityEvent('ping'));

            return response()->json(['message' => 'ping'], 200);
        } catch (BroadcastException $e) {
            return response()->json([
                'error' => 'Service Unavailable'
            ], 503);
        }
    }

    public function ping_server()
    {
        try {
            event(new ServerCheckEvent());

            return response()->json(['message' => 'ping'], 200);
        } catch (BroadcastException $e) {
            return response()->json([
                'error' => 'Service Unavailable'
            ], 503);
        }
    }

    /**
     * Websocket
     */
    public function discover_on_activity_channel()
    {
        $data = request()->all();
        $user_session = UserController::manage_user_session(request());
        $user_session->url = $data['url'];
        $user_session->ip = request()->ip();
        UserController::save_user_session($user_session);
        event(new ActivityEventAdmin(["discover_user" => $user_session]));

        return response()->json(['user' => $user_session], 200);
    }
}
