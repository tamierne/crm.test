<?php

namespace App\Listeners;

use App\Events\UrlParserStarted;

class SendStartParsingSuperAdminNotification
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
     * @param  \App\Events\UrlParserStarted  $event
     * @return void
     */
    public function handle(UrlParserStarted $event)
    {
        //
    }
}
