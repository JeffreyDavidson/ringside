<?php

namespace App\Models;

use App\Traits\HasCachedAttributes;
use Illuminate\Database\Eloquent\Model;

abstract class SingleRosterMember extends Model
{
    use HasCachedAttributes,
        Concerns\CanBeSuspended,
        Concerns\CanBeInjured,
        Concerns\CanBeRetired,
        Concerns\CanBeEmployed,
        Concerns\CanBeBooked;

    public function retire()
    {
        if ($this->checkIsPendingEmployment() || $this->checkIsRetired()) {
            throw new CannotBeRetiredException;
        }

        if ($this->checkIsSuspended()) {
            $this->reinstate();
        }

        if ($this->checkIsInjured()) {
            $this->recover();
        }

        $this->retirements()->create(['started_at' => now()]);

        return $this->touch();
    }
}
