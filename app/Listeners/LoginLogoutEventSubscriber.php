<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\Database\Auth\UserLoggedInNotification;
use App\Notifications\Database\Auth\UserLoggedOutNotification;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class LoginLogoutEventSubscriber
{
    private $admin;
    public function __construct()
    {
        $this->admin = User::role('super-admin')->first();
    }

    public function handleLogin(Login $event)
    {
        $this->admin->notify(new UserLoggedInNotification($event->user));
    }

    public function handleLogout(Logout $event)
    {
        $this->admin->notify(new UserLoggedOutNotification($event->user));
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
            Login::class => 'handleLogin',
            Logout::class => 'handleLogout',
        ];
    }
}
