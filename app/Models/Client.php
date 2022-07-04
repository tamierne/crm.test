<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;

class Client extends BaseModel
{
    use HasFactory, SoftDeletes, SoftCascadeTrait;

    protected $fillable = [
        'name',
        'VAT',
        'address',
    ];

    protected $softCascade = ['projects'];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function scopeActiveClients()
    {
        return $this->whereHas('projects', fn(Builder $builder) => $builder->recent());
    }
}
