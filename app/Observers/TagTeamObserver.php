<?php

namespace App\Observers;

use App\Models\TagTeam;
use App\Enums\TagTeamStatus;

class TagTeamObserver
{
    /**
     * Handle the Tag Team "saving" event.
     *
     * @param  App\Models\TagTeam  $tagteam
     * @return void
     */
    public function saving(TagTeam $tagteam)
    {
        if ($tagteam->checkIsRetired()) {
            $tagteam->status = TagTeamStatus::RETIRED;
        } elseif ($tagteam->checkIsSuspended()) {
            $tagteam->status = TagTeamStatus::SUSPENDED;
        } elseif ($tagteam->checkIsBookable()) {
            $tagteam->status = TagTeamStatus::BOOKABLE;
        } else {
            $tagteam->status = TagTeamStatus::PENDING_EMPLOYMENT;
        }
    }
}
