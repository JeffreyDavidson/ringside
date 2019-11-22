<?php

namespace App\Models;

use App\Traits\HasCachedAttributes;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\CannotBeRetiredException;
use Illuminate\Database\Eloquent\SoftDeletes;

class Title extends Model
{
    use SoftDeletes,
        HasCachedAttributes,
        Concerns\CanBeRetired,
        Concerns\CanBeCompeted,
        Concerns\CanBeIntroduced;

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
    protected $dates = ['introduced_at'];

    /**
     *
     */
    public function retire()
    {
        if ($this->checkIsPendingIntroduction() || $this->checkIsRetired()) {
            throw new CannotBeRetiredException;
        }

        $this->retirements()->create(['started_at' => now()]);

        return $this->touch();
    }
}
