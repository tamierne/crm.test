<?php

namespace App\Notifications\Database\Auth;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserLoggedOutNotification extends Notification
{
    use Queueable;

    public User $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
            'user_name' => $this->user->name,
            'action' => 'User logged out',
            'details' => ($this->user->google_id || $this->user->github_id) ? 'Signed via google/github' : 'No data',
            'when' => now()->parse()->format('m/d/Y'),
        ];
    }
}
