<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Project extends BaseModel
{
    use HasFactory, SoftDeletes, SoftCascadeTrait, LogsActivity;

    protected $touches = ['client'];
    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['status:id,name'];
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
        'client_id',
        'status_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'user_id',
        'client_id',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    protected $softCascade = ['tasks'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('projects')
            ->logOnly(['title', 'user.name', 'status.name'])
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
     * @return BelongsTo|Client
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withTrashed();
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

//    public function status()
//    {
//        return $this->morphOne(Status::class, 'status', 'status_id');
//    }

    /**
     * @return HasMany|Collection|Task
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function scopeRecent($query)
    {
        return $query->where('updated_at', '>', Carbon::now()->subDays(30));
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
