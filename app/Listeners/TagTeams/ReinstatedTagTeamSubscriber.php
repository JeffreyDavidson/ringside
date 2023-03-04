<?php

namespace App\Listeners\TagTeams;

use App\Actions\Wrestlers\ReinstateAction;
use App\Events\TagTeams\TagTeamReinstated;
use Illuminate\Events\Dispatcher;

class ReinstatedTagTeamSubscriber
{
    /**
     * Handle tag team reinstated events.
     */
    public function handleTagTeamReinstated(TagTeamReinstated $event): void
    {
        $event->tagTeam->currentWrestlers
            ->each(fn ($wrestler) => ReinstateAction::run($wrestler, $event->reinstatementDate));
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            TagTeamReinstated::class,
            [ReinstatedTagTeamSubscriber::class, 'handleTagTeamReinstated']
        );
    }
}
