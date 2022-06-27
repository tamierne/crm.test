<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function status()
    {
        return $this->morphOne(Status::class, 'statusable');
    }
}
