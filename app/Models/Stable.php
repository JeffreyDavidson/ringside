<?php

namespace App\Models;

use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stable extends Model
{
    use SoftDeletes,
        Sluggable;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->is_active = $model->started_at->lte(today());
        });
    }

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['started_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all wrestlers that have been members of the stable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphByMany
     */
    public function wrestlers()
    {
        return $this->morphedByMany(Wrestler::class, 'member');
    }

    /**
     * Get all tag teams that have been members of the stable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphByMany
     */
    public function tagteams()
    {
        return $this->morphedByMany(TagTeam::class, 'member');
    }

    /**
     * Add wrestlers to the stable.
     *
     * @param  array  $wrestlerIds
     * @return $this
     */
    public function addWrestlers($wrestlerIds)
    {
        foreach ($wrestlerIds as $wrestlerId) {
            $this->wrestlers()->attach($wrestlerId);
        }

        return $this;
    }

    /**
     * Add tag teams to the stable.
     *
     * @param  array  $tagteamIds
     * @return $this
     */
    public function addTagTeams($tagteamIds)
    {
        foreach ($tagteamIds as $tagteamId) {
            $this->tagteams()->attach($tagteamId);
        }

        return $this;
    }
}
