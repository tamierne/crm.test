<?php

namespace App\Notifications;

use App\Models\ParserTask;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParserStartedNotification extends Notification implements ShouldQueue
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


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->parserTask->user_id,
            'user_name' => $this->parserTask->user->name,
            'action' => 'Started URL parser',
            'url' => $this->parserTask->url,
            'started_at' => $this->parserTask->started_at,
        ];
    }
}