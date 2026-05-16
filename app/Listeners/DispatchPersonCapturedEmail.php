<?php

namespace App\Listeners;

use App\Events\PersonCaptured;
use App\Jobs\SendPersonCapturedEmailJob;

class DispatchPersonCapturedEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\PersonCaptured  $event
     * @return void
     */
    public function handle(PersonCaptured $event)
    {
        dispatch(new SendPersonCapturedEmailJob($event->person));
    }
}
