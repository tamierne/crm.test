<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends BaseModel
{
    use HasFactory, SoftDeletes;

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
