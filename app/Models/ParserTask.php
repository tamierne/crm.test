<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ParserTask extends BaseModel
{
    use HasFactory, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'url',
        'user_id',
        'status_id',
        'result',
    ];

    protected $casts = [
        'started_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('parsers')
            ->logOnly(['url', 'result', 'user.name', 'status.name'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * @return BelongsTo|Project
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * @return BelongsTo|Status
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class)->withTrashed();
    }

    public function getStartedAtAttribute($value)
    {
        if($value) {
            return Carbon::parse($value)->format('m/d/Y');
        } else {
            return $value;
        }
    }

    public function getFinishedAtAttribute($value)
    {
        if($value) {
            return Carbon::parse($value)->format('m/d/Y');
        } else {
            return $value;
        }
    }

//    public function statusable()
//    {
//        return $this->morphOne(Status::class, 'statusable');
//    }
}
