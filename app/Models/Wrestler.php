<?php

namespace App\Models;

use App\Casts\HeightCast;
use App\Enums\WrestlerStatus;
use App\Models\Contracts\Bookable;
use App\Models\Contracts\Manageable;
use App\Models\Contracts\StableMember;
use App\Models\Contracts\TagTeamMember;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wrestler extends SingleRosterMember implements Bookable, Manageable, TagTeamMember, StableMember
{
    use SoftDeletes,
        HasFactory,
        Concerns\Bookable,
        Concerns\CanJoinStable,
        Concerns\CanJoinTagTeam,
        Concerns\Manageable,
        Concerns\Unguarded,
        HasRelationships;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saving(function ($wrestler) {
            $wrestler->updateStatus();
        });
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wrestlers';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => WrestlerStatus::class,
        'height' => HeightCast::class,
    ];

    /**
     * Get the user assigned to the wrestler.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Update the status for the wrestler.
     *
     * @return void
     */
    public function updateStatus()
    {
        if ($this->isCurrentlyEmployed()) {
            if ($this->isInjured()) {
                $this->status = WrestlerStatus::INJURED;
            } elseif ($this->isSuspended()) {
                $this->status = WrestlerStatus::SUSPENDED;
            } elseif ($this->isBookable()) {
                $this->status = WrestlerStatus::BOOKABLE;
            }
        } elseif ($this->hasFutureEmployment()) {
            $this->status = WrestlerStatus::FUTURE_EMPLOYMENT;
        } elseif ($this->isReleased()) {
            $this->status = WrestlerStatus::RELEASED;
        } elseif ($this->isRetired()) {
            $this->status = WrestlerStatus::RETIRED;
        } else {
            $this->status = WrestlerStatus::UNEMPLOYED;
        }
    }

    /**
     * Updates a wrestler's status and saves.
     *
     * @return void
     */
    public function updateStatusAndSave()
    {
        $this->updateStatus();
        $this->save();
    }

    /**
     * Updates a manager's status and saves.
     *
     * @return void
     */
    public function removeFromCurrentTagTeam()
    {
        $this->tagTeams()->updateExistingPivot($this->currentTagTeam->id, [
            'left_at' => now(),
        ]);
    }
}
