<?php

namespace App\Observers;

use App\Models\Referee;
use App\Enums\RefereeStatus;

class RefereeObserver
{
    /**
     * Handle the Referee "saving" event.
     *
     * @param  App\Models\Referee  $referee
     * @return void
     */
    public function saving(Referee $referee)
    {
        if ($referee->checkIsRetired()) {
            $referee->status = RefereeStatus::RETIRED;
        } elseif ($referee->checkIsInjured()) {
            $referee->status =  RefereeStatus::INJURED;
        } elseif ($referee->checkIsSuspended()) {
            $referee->status = RefereeStatus::SUSPENDED;
        } elseif ($referee->checkIsBookable()) {
            $referee->status = RefereeStatus::BOOKABLE;
        } else {
            $referee->status = RefereeStatus::PENDING_EMPLOYMENT;
        }
    }
}
