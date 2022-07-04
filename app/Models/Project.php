<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;

class Project extends BaseModel
{
    use HasFactory, SoftDeletes, SoftCascadeTrait;

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

    protected $softCascade = ['tasks'];

    public function getDeadlineAttribute($value)
    {
        return Carbon::parse($value)->format('m/d/Y');
    }

    public function client()
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function scopeRecent($query)
    {
        return $query->where('updated_at', '>', Carbon::now()->subDays(30));
    }

    public function scopeByStatus($query, $status)
    {
        $query->whereHas('status', fn($query) => $query->where('name', $status));
    }
}
