<?php

namespace App\Listeners;

use App\Events\Client\ClientCreated;
use App\Events\Client\ClientDeleted;
use App\Events\Client\ClientUpdated;
use App\Models\User;
use App\Notifications\Database\Client\ClientCreatedNotification;
use App\Notifications\Database\Client\ClientDeletedNotification;
use App\Notifications\Database\Client\ClientUpdatedNotification;

class ClientEventSubscriber
{
    private User $admin;

    public function __construct()
    {
        $this->admin = User::role('super-admin')->first();
    }

    public function handleClientCreated(ClientCreated $event)
    {
        $this->admin->notify(new ClientCreatedNotification($event->client));
    }

    public function handleClientUpdated(ClientUpdated $event)
    {
        $this->admin->notify(new ClientUpdatedNotification($event->client));
    }

    public function handleClientDeleted(ClientDeleted $event)
    {
        $this->admin->notify(new ClientDeletedNotification($event->client));
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
            ClientCreated::class => 'handleClientCreated',
            ClientUpdated::class => 'handleClientUpdated',
            ClientDeleted::class => 'handleClientDeleted'
        ];
    }
}
