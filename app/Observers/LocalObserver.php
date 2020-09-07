<?php

namespace App\Observers;

use App\Models\Local;
use App\Events\LocalActualizado;
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
        //
    }

    /**
     * Handle the local "updated" event.
     *
     * @param  \App\Models\Local  $local
     * @return void
     */
    public function updated(Local $local)
    {

        $guests = Guest::whereNotNull('id_user')->get();
        $admin = Session::get(SessionConstants::ADMIN_USER);

        foreach($guests as $guest)
        {
            Notification::send($guest,new Push("Local modificado", "El local ".$local->titulo." ha sido modificado", $admin->nombre ));
        }

       event(new LocalActualizado($local));
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
