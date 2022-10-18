<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Task extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $touches = ['project'];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'status:id,name',
        'project:id,title',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'deadline',
        'user_id',
        'project_id',
        'status_id',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'user_id',
        'project_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('tasks')
            ->logOnly(['title', 'user.name', 'project.title', 'status.name'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * @return string
     */
    public function getDeadlineParsedAttribute(): string
    {
        return Carbon::parse($this->deadline)->format('m/d/Y');
    }

    /**
     * @return BelongsTo|Project
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class)->withTrashed();
    }

    /**
     * @return BelongsTo|User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo|Status
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->whereHas('status', fn($query) => $query->where('name', $status));
    }

    public function scopeActive($query)
    {
        return $query->whereNot('status_id', '=', Status::STATUS_COMPLETED)
            ->whereNot('status_id', '=', Status::STATUS_CANCELLED);
    }
}
