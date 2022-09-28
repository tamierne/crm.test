<?php

namespace App\Notifications\Database\Client;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ClientUpdatedNotification extends Notification
{
    use Queueable;

    public Client $client;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
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
            'user_name' => auth()->user()->name,
            'action' => 'Updated client info',
            'details' => $this->client->name,
            'when' => $this->client->updated_at,
        ];
    }
}
