<?php

namespace App\Notifications\Database\UrlParser;

use App\Models\ParserTask;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ParserAddedNotification extends Notification
{
    use Queueable;

    public ParserTask $parserTask;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ParserTask $parserTask)
    {
        $this->parserTask = $parserTask;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'user_name' => $this->parserTask->user->name,
            'action' => 'Added URL parser',
            'details' => $this->parserTask->url,
            'when' => $this->parserTask->created_at,
        ];
    }
}
