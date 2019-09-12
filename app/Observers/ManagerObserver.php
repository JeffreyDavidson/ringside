<?php

namespace App\Observers;

use App\Models\Manager;
use App\Enums\ManagerStatus;

class ManagerObserver
{
    /**
     * Handle the Manager "saving" event.
     *
     * @param  App\Models\Manager  $manager
     * @return void
     */
    public function saving(Manager $manager)
    {
        if ($manager->checkIsRetired()) {
            $manager->status = ManagerStatus::RETIRED;
        } elseif ($manager->checkIsInjured()) {
            $manager->status =  ManagerStatus::INJURED;
        } elseif ($manager->checkIsSuspended()) {
            $manager->status = ManagerStatus::SUSPENDED;
        } elseif ($manager->checkIsBookable()) {
            $manager->status = ManagerStatus::BOOKABLE;
        } else {
            $manager->status = ManagerStatus::PENDING_EMPLOYMENT;
        }
    }
}
