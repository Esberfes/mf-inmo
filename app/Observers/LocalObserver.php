<?php

namespace App\Observers;

use App\Models\Local;
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
        // Se obtienen solo usuarios registrados
        $guests = Guest::whereNotNull('id_user')->get();

        // Se obtiene el administrador actual para saber quien ha relizado la operacion
        $admin = Session::get(SessionConstants::ADMIN_USER);

        // Se notifica via Service Worker a los administradores
        foreach ($guests as $guest) {
            Notification::send($guest, new Push("Nuevo local", "Se ha creado el local " . $local->titulo, $admin->nombre));
        }
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


        if($local->getOriginal('activo') == 0 && $local->activo == 1) { // Activado

            // Se notifica via WebSocket a todos los clientes de que se ha activado un nuevo local
            foreach ($guests as $guest) {
                Notification::send($guest, new Push("Local modificado", "El local " . $local->titulo . " ha sido activado", $admin->nombre));
            }
        } elseif($local->getOriginal('activo') == 1 && $local->activo == 0) { // Desactivado

            // Se notifica via Service Worker a los administradores
            foreach ($guests as $guest) {
                Notification::send($guest, new Push("Local modificado", "El local " . $local->titulo . " ha sido desactovado", $admin->nombre));
            }
        } elseif($local->activo == 1) {
            // Se notifica via Service Worker a los administradores
            foreach ($guests as $guest) {
                Notification::send($guest, new Push("Local modificado", "El local " . $local->titulo . " ha sido modificado", $admin->nombre));
            }
        } else {
             // Se notifica via Service Worker a los administradores
             foreach ($guests as $guest) {
                Notification::send($guest, new Push("Local modificado", "El local " . $local->titulo . " ha sido modificado", $admin->nombre));
            }
        }
    }

    /**
     * Handle the local "deleted" event.
     *
     * @param  \App\Models\Local  $local
     * @return void
     */
    public function deleted(Local $local)
    {
         // Se obtiene el administrador actual para saber quien ha relizado la operacion
         $admin = Session::get(SessionConstants::ADMIN_USER);

        // Se obtienen solo usuarios registrados
        $guests = Guest::whereNotNull('id_user')->get();
        foreach ($guests as $guest) {
            Notification::send($guest, new Push("Local eliminado", "El local " . $local->titulo . " ha sido eliminado", $admin->nombre));
        }
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
