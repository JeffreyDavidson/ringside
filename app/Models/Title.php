<?php

namespace App\Models;

use App\Enums\TitleStatus;
use App\Models\Contracts\Activatable;
use App\Models\Contracts\Deactivatable;
use App\Models\Contracts\Retirable;
use App\Models\Contracts\Unretirable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Title extends Model implements Activatable, Deactivatable, Retirable, Unretirable
{
    use SoftDeletes,
        HasFactory,
        Concerns\Activatable,
        Concerns\Deactivatable,
        Concerns\Retirable,
        Concerns\Unguarded;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saving(function ($title) {
            $title->updateStatus();
        });
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'titles';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => TitleStatus::class,
    ];

    /**
     * Determine if the model can be retired.
     *
     * @return bool
     */
    public function canBeRetired()
    {
        if ($this->isNotInActivation()) {
            return false;
        }

        return true;
    }

    /**
     * Update the status for the title.
     *
     * @return void
     */
    public function updateStatus()
    {
        if ($this->isCurrentlyActivated()) {
            $this->status = TitleStatus::ACTIVE;
        } elseif ($this->hasFutureActivation()) {
            $this->status = TitleStatus::FUTURE_ACTIVATION;
        } elseif ($this->isDeactivated()) {
            $this->status = TitleStatus::INACTIVE;
        } elseif ($this->isRetired()) {
            $this->status = TitleStatus::RETIRED;
        } else {
            $this->status = TitleStatus::UNACTIVATED;
        }
    }

    /**
     * Updates a title's status and saves.
     *
     * @return void
     */
    public function updateStatusAndSave()
    {
        $this->updateStatus();
        $this->save();
    }
}
