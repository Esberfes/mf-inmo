<?php

namespace App\Observers;

use App\Models\Usuario;
use App\Models\LocalSolicitud;
use Notification;
use App\Guest;
use App\Jobs\SendEmail;
use App\Jobs\SendEmailSolicitudAdmins;
use App\Notifications\Push;

class LocalSolicitudObserver
{
    /**
     *
     */
    public function created(LocalSolicitud $solicitud)
    {
        $guests = Guest::whereNotNull('id_user')->get();

        // Notificación push a los administradores
        foreach ($guests as $guest) {
            Notification::send($guest, new Push("Solicitud de contacto", "Nueva solicitud para el local " . $solicitud->local->titulo, $solicitud->nombre . " - " . $solicitud->email));
        }

        // email a los administradores
        $admins = Usuario::all();

        foreach ($admins as $admin) {
            SendEmailSolicitudAdmins::dispatch([
                'solicitud' => $solicitud,
                'admin' => $admin
            ]);
        }

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
    public function updated(LocalSolicitud $solicitud)
    {
    //
    }

    /**
     * Handle the solicitud "deleted" event.
     *
     * @param  \App\Solicitud  $solicitud
     * @return void
     */
    public function deleted(LocalSolicitud $solicitud)
    {
    //
    }

    /**
     * Handle the solicitud "restored" event.
     *
     * @param  \App\Solicitud  $solicitud
     * @return void
     */
    public function restored(LocalSolicitud $solicitud)
    {
    //
    }

    /**
     * Handle the solicitud "force deleted" event.
     *
     * @param  \App\Solicitud  $solicitud
     * @return void
     */
    public function forceDeleted(LocalSolicitud $solicitud)
    {
    //
    }
}
