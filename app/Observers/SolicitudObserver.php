<?php

namespace App\Observers;

use App\Models\Solicitud;

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
        //
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
