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
        if ($manager->isRetired()) {
            $manager->status = ManagerStatus::RETIRED;
        } elseif ($manager->isInjured()) {
            $manager->status =  ManagerStatus::INJURED;
        } elseif ($manager->isSuspended()) {
            $manager->status = ManagerStatus::SUSPENDED;
        } elseif ($manager->isPendingEmployment()) {
            $manager->status = ManagerStatus::PENDING_EMPLOYMENT;
        } else {
            $manager->status = ManagerStatus::AVAILABLE;
        }
    }
}
