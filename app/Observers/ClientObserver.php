<?php

namespace App\Observers;

use App\Models\Client;
use App\Models\User;
use App\Notifications\Database\Client\ClientCreatedNotification;
use App\Notifications\Database\Client\ClientDeletedNotification;
use App\Notifications\Database\Client\ClientUpdatedNotification;

class ClientObserver
{
    private User $admin;

    public function __construct()
    {
        $this->admin = User::role('super-admin')->first();
    }

    /**
     * Handle the Client "created" event.
     *
     * @param  \App\Models\Client  $client
     * @return void
     */
    public function created(Client $client)
    {
        $this->admin->notify(new ClientCreatedNotification($client));
    }

    /**
     * Handle the Client "updated" event.
     *
     * @param  \App\Models\Client  $client
     * @return void
     */
    public function updated(Client $client)
    {
        $this->admin->notify(new ClientUpdatedNotification($client));
    }

    /**
     * Handle the Client "deleted" event.
     *
     * @param  \App\Models\Client  $client
     * @return void
     */
    public function deleted(Client $client)
    {
        $this->admin->notify(new ClientDeletedNotification($client));
    }

    /**
     * Handle the Client "restored" event.
     *
     * @param  \App\Models\Client  $client
     * @return void
     */
    public function restored(Client $client)
    {
        //
    }

    /**
     * Handle the Client "force deleted" event.
     *
     * @param  \App\Models\Client  $client
     * @return void
     */
    public function forceDeleted(Client $client)
    {
        //
    }
}
