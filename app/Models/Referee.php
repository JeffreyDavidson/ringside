<?php

namespace App\Models;

use App\Enums\RefereeStatus;
use App\Models\Contracts\Bookable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referee extends SingleRosterMember implements Bookable
{
    use SoftDeletes,
        HasFactory,
        Concerns\Bookable,
        Concerns\HasFullName,
        Concerns\Unguarded;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saving(function ($referee) {
            $referee->updateStatus();
        });
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'referees';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => RefereeStatus::class,
    ];

    /**
     * Update the status for the referee.
     *
     * @return void
     */
    public function updateStatus()
    {
        if ($this->isCurrentlyEmployed()) {
            if ($this->isInjured()) {
                $this->status = RefereeStatus::INJURED;
            } elseif ($this->isSuspended()) {
                $this->status = RefereeStatus::SUSPENDED;
            } elseif ($this->isBookable()) {
                $this->status = RefereeStatus::BOOKABLE;
            }
        } elseif ($this->hasFutureEmployment()) {
            $this->status = RefereeStatus::FUTURE_EMPLOYMENT;
        } elseif ($this->isReleased()) {
            $this->status = RefereeStatus::RELEASED;
        } elseif ($this->isRetired()) {
            $this->status = RefereeStatus::RETIRED;
        } else {
            $this->status = RefereeStatus::UNEMPLOYED;
        }
    }

    /**
     * Updates a referee's status and saves.
     *
     * @return void
     */
    public function updateStatusAndSave()
    {
        $this->updateStatus();
        $this->save();
    }
}
