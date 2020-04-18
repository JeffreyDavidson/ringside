<?php

namespace App\Models;

use App\Enums\TitleStatus;
use App\Exceptions\CannotBeRetiredException;
use App\Traits\HasCachedAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Title extends Model
{
    use SoftDeletes,
        HasCachedAttributes,
        Concerns\CanBeRetired,
        Concerns\CanBeCompeted,
        Concerns\CanBeActivated,
        Concerns\CanBeRetired;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => TitleStatus::class,
    ];

    public function retire($retiredAt = null)
    {
        $retiredDate = $retiredAt ?: now();
        $this->currentActivation()->update(['ended_at' => $retiredDate]);

        $this->retirements()->create(['started_at' => $retiredDate]);

        return $this->touch();
    }

    public function unretire($unretiredAt = null)
    {
        $unretiredDate = $unretiredAt ?: now();

        $this->currentRetirement()->update(['ended_at' => $unretiredDate]);
        $this->activate($unretiredDate);

        return $this->touch();
    }

    /**
     * Determine if the model can be retired.
     *
     * @return bool
     */
    public function canBeRetired()
    {
        if ($this->isUnactivated()) {
            return false;
        }

        if ($this->hasFutureActivation()) {
            return false;
        }

        if ($this->isRetired()) {
            return false;
        }

        return true;
    }
}
