<?php

namespace App\Observers;

use App\Models\Usuario;
use App\Models\Solicitud;
use Notification;
use App\Guest;
use App\Jobs\SendEmail;
use App\Jobs\SendEmailSolicitudAnunciante;
use App\Notifications\Push;

class SolicitudObserver
{
    /**
     *
     */
    public function created(Solicitud $solicitud)
    {
        $guests = Guest::whereNotNull('id_user')->get();

        // Notificación push a los administradores
        foreach ($guests as $guest) {
            Notification::send($guest, new Push("Solicitud de anunciante", "Nueva solicitud anunciante ", $solicitud->nombre . " - " . $solicitud->email));
        }

        // email a los administradores
        $admins = Usuario::all();

        foreach ($admins as $admin) {
            SendEmailSolicitudAnunciante::dispatch([
                'solicitud' => $solicitud,
                'admin' => $admin
            ]);
        }
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
