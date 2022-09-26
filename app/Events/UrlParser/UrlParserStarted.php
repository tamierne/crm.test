<?php

namespace App\Events\UrlParser;

use App\Models\ParserTask;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UrlParserStarted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ParserTask $parserTask;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ParserTask $parserTask)
    {
        $this->parserTask = $parserTask;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
