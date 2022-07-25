<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Client extends BaseModel implements HasMedia
{
    use HasFactory, SoftDeletes, SoftCascadeTrait, InteractsWithMedia;

    protected $fillable = [
        'name',
        'VAT',
        'address',
    ];

    protected $softCascade = ['projects'];

    /**
     * @return HasMany|Collection|Project
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function scopeActiveClients()
    {
        return $this->whereHas('projects', fn(Builder $builder) => $builder->recent());
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }
}
