<?php

namespace App\Listeners\TagTeams;

use App\Actions\Wrestlers\RetireAction;
use App\Events\TagTeams\TagTeamRetired;
use Illuminate\Events\Dispatcher;

class RetiredTagTeamSubscriber
{
    /**
     * Handle tag team reinstated events.
     */
    public function handleTagTeamRetired(TagTeamRetired $event): void
    {
        $event->tagTeam->currentWrestlers
            ->each(fn ($wrestler) => RetireAction::run($wrestler, $event->retirementDate));
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            TagTeamRetired::class,
            [RetiredTagTeamSubscriber::class, 'handleTagTeamRetired']
        );
    }
}
