<?php

namespace App\Observers;

use App\Models\TagTeam;
use App\Enums\TagTeamStatus;

class TagTeamObserver
{
    /**
     * Handle the Tag Team "saving" event.
     *
     * @param  App\Models\TagTeam  $tagTeam
     * @return void
     */
    public function saving(TagTeam $tagTeam)
    {
        if ($tagTeam->isRetired()) {
            $tagTeam->status = TagTeamStatus::RETIRED;
        } elseif ($tagTeam->isSuspended()) {
            $tagTeam->status = TagTeamStatus::SUSPENDED;
        } elseif ($tagTeam->isBookable()) {
            $tagTeam->status = TagTeamStatus::BOOKABLE;
        } else {
            $tagTeam->status = TagTeamStatus::PENDING_EMPLOYMENT;
        }
    }
}
