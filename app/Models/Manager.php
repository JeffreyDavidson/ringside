<?php

namespace App\Models;

use App\Enums\ManagerStatus;
use App\Models\Contracts\StableMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manager extends SingleRosterMember implements StableMember
{
    use SoftDeletes,
        HasFactory,
        Concerns\HasFullName,
        Concerns\Manageables,
        Concerns\StableMember,
        Concerns\Unguarded;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saving(function ($manager) {
            $manager->updateStatus();
        });
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'managers';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => ManagerStatus::class,
    ];

    /**
     * Get the user belonging to the manager.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include available managers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', ManagerStatus::AVAILABLE);
    }

    /**
     * Check to see if the manager is available.
     *
     * @return bool
     */
    public function isAvailable()
    {
        return $this->currentEmployment()->exists();
    }

    /**
     * Update the status for the manager.
     *
     * @return $this
     */
    public function updateStatus()
    {
        $this->status = match (true) {
            $this->isCurrentlyEmployed() => match (true) {
                $this->isInjured() => ManagerStatus::INJURED,
                $this->isSuspended() => ManagerStatus::SUSPENDED,
                $this->isAvailable() => ManagerStatus::AVAILABLE,
            },
            $this->hasFutureEmployment() => ManagerStatus::FUTURE_EMPLOYMENT,
            $this->isReleased() => ManagerStatus::RELEASED,
            $this->isRetired() => ManagerStatus::RETIRED,
            default => ManagerStatus::UNEMPLOYED
        };

        return $this;
    }
}
