<?php

namespace App\Observers;

use App\Models\Wrestler;
use App\Enums\WrestlerStatus;

class WrestlerObserver
{
    /**
     * Handle the Wrestler "saving" event.
     *
     * @param  App\Models\Wrestler  $wrestler
     * @return void
     */
    public function saving(Wrestler $wrestler)
    {
        if ($wrestler->checkIsRetired()) {
            $wrestler->status = WrestlerStatus::RETIRED;
        } elseif ($wrestler->checkIsInjured()) {
            $wrestler->status =  WrestlerStatus::INJURED;
        } elseif ($wrestler->checkIsSuspended()) {
            $wrestler->status = WrestlerStatus::SUSPENDED;
        } elseif ($wrestler->checkIsPendingEmployment()) {
            $wrestler->status = WrestlerStatus::PENDING_EMPLOYMENT;
        } else {
            $wrestler->status = WrestlerStatus::BOOKABLE;
        }
    }
}
