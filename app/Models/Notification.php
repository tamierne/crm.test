<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'admin_read_at' => 'datetime',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'notifiable_id');
    }

    /**
     * Scope a query to only include unread by admin notifications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdminUnread(Builder $query)
    {
        return $query->select([
            'id', 'data'
        ])->whereNull('admin_read_at');
    }

    /**
     * Mark all notifications as read by admin.
     *
     * @return void
     */
    protected static function markAllAsAdminRead()
    {
        Notification::whereNull('admin_read_at')
            ->update([
                'admin_read_at' => now(),
            ]);
    }
}
