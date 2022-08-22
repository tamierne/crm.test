<?php

namespace App\Listeners;

use App\Events\UrlParserFinished;

class SendFinishedParsingSuperAdminNotification
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
     * @param  \App\Events\UrlParserFinished  $event
     * @return void
     */
    public function handle(UrlParserFinished $event)
    {
        //
    }
}
