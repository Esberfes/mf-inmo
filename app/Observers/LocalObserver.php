<?php

namespace App\Observers;

use App\Models\Local;
use App\Events\LocalActualizadoEvent;
use App\Events\LocalCreadoEvent;
use App\Notifications\Push;
use App\Guest;
use Notification;
use Illuminate\Support\Facades\Session;
use App\Constants\SessionConstants;

class LocalObserver
{
    /**
     * Handle the local "created" event.
     *
     * @param  \App\Models\Local  $local
     * @return void
     */
    public function created(Local $local)
    {
        $guests = Guest::whereNotNull('id_user')->get();

        // Se obtiene el administrador actual para saber quien ha relizado la operacion
        $admin = Session::get(SessionConstants::ADMIN_USER);

        // Se notifica via Service Worker
        foreach($guests as $guest)
        {
            Notification::send($guest,new Push("Nuevo local", "Se ha creado el local ".$local->titulo, $admin->nombre ));
        }

        // Se notifica via WebSocket
       event(new LocalCreadoEvent($local));
    }

    /**
     * Handle the local "updated" event.
     *
     * @param  \App\Models\Local  $local
     * @return void
     */
    public function updated(Local $local)
    {
        // Se obtienen solo usuarios registrados
        $guests = Guest::whereNotNull('id_user')->get();

        // Se obtiene el administrador actual para saber quien ha relizado la operacion
        $admin = Session::get(SessionConstants::ADMIN_USER);

        // Se notifica via Service Worker
        foreach($guests as $guest)
        {
            Notification::send($guest,new Push("Local modificado", "El local ".$local->titulo." ha sido modificado", $admin->nombre ));
        }

        // Se notifica via WebSocket
       event(new LocalActualizadoEvent($local));
    }

    /**
     * Handle the local "deleted" event.
     *
     * @param  \App\Models\Local  $local
     * @return void
     */
    public function deleted(Local $local)
    {
        //
    }

    /**
     * Handle the local "restored" event.
     *
     * @param  \App\Models\Local  $local
     * @return void
     */
    public function restored(Local $local)
    {
        //
    }

    /**
     * Handle the local "force deleted" event.
     *
     * @param  \App\Models\Local  $local
     * @return void
     */
    public function forceDeleted(Local $local)
    {
        //
    }
}
