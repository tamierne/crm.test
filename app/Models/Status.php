<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends BaseModel
{
    use HasFactory, SoftDeletes;

    public const STATUS_QUEUED   = 1;
    public const STATUS_PROCESSING = 2;
    public const STATUS_ON_HOLD  = 3;
    public const STATUS_COMPLETED  = 4;
    public const STATUS_CANCELLED  = 5;


    protected $fillable = [
        'name',
    ];

//    public function tasks()
//    {
//        return $this->morphMany(Task::class, 'statusable');
//    }
//
//    public function projects()
//    {
//        return $this->morphMany(Project::class, 'statusable');
//    }
//
//    public function parserTasks()
//    {
//        return $this->morphMany(ParserTask::class, 'statusable');
//    }
}
