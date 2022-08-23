<?php

namespace App\Listeners;

use App\Events\UrlParserAdded;
use App\Events\UrlParserFinished;
use App\Events\UrlParserStarted;
use App\Notifications\ParserAddedNotification;
use App\Notifications\ParserStartedNotification;

class ParserTaskEventSubscriber
{
    public function handleUrlParserAdded(UrlParserAdded $event)
    {
        $event->parserTask->user->notify(new ParserAddedNotification($event->parserTask));
    }

    public function handleUrlParserStarted(UrlParserStarted $event)
    {
        $event->parserTask->user->notify(new ParserStartedNotification($event->parserTask));
    }

    public function handleUrlParserFinished(UrlParserFinished $event)
    {
//
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe($events)
    {
        return [
            UrlParserAdded::class => 'handleUrlParserAdded',
            UrlParserStarted::class => 'handleUrlParserStarted',
//            UrlParserFinished::class => 'handleUrlParserFinished',
        ];
    }
}
