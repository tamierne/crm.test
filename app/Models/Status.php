<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Status extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name'
        // 'status_id',
        // 'statusableId',
        // 'statusableType',
    ];

    // public function statusable()
    // {
    //     return $this->morphTo();
    // }
}
