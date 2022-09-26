<?php

namespace App\Listeners;

use App\Events\UrlParser\UrlParserAdded;
use App\Events\UrlParser\UrlParserFinished;
use App\Events\UrlParser\UrlParserStarted;
use App\Notifications\Database\UrlParser\ParserAddedNotification;
use App\Notifications\Database\UrlParser\ParserStartedNotification;

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
