<?php

namespace App\Observers;

use App\Models\Solicitud;
use Notification;
use App\Guest;
use App\Jobs\SendEmail;
use App\Notifications\Push;

class SolicitudObserver
{
    /**
     * Handle the solicitud "created" event.
     *
     * @param  \App\Solicitud  $solicitud
     * @return void
     */
    public function created(Solicitud $solicitud)
    {
        $guests = Guest::whereNotNull('id_user')->get();

        // Notificación push a los administradores
        foreach ($guests as $guest) {
            Notification::send($guest, new Push("Solicitud de contacto", "Nueva solicitud para el local " . $solicitud->local->titulo, $solicitud->nombre . " - " . $solicitud->email));
        }

        // TODO notificar por websockets a los administradores

        // TODO email a los administradores

        // Se envia email al interesado
        SendEmail::dispatch([
            'nombre' => $solicitud->nombre,
            'email' => $solicitud->email,
            'local' => $solicitud->local
        ]);
    }

    /**
     * Handle the solicitud "updated" event.
     *
     * @param  \App\Solicitud  $solicitud
     * @return void
     */
    public function updated(Solicitud $solicitud)
    {
        //
    }

    /**
     * Handle the solicitud "deleted" event.
     *
     * @param  \App\Solicitud  $solicitud
     * @return void
     */
    public function deleted(Solicitud $solicitud)
    {
        //
    }

    /**
     * Handle the solicitud "restored" event.
     *
     * @param  \App\Solicitud  $solicitud
     * @return void
     */
    public function restored(Solicitud $solicitud)
    {
        //
    }

    /**
     * Handle the solicitud "force deleted" event.
     *
     * @param  \App\Solicitud  $solicitud
     * @return void
     */
    public function forceDeleted(Solicitud $solicitud)
    {
        //
    }
}
