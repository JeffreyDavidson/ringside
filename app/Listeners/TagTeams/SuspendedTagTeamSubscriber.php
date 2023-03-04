<?php

namespace App\Listeners\TagTeams;

use App\Actions\Wrestlers\SuspendAction;
use App\Events\TagTeams\TagTeamSuspended;
use Illuminate\Events\Dispatcher;

class SuspendedTagTeamSubscriber
{
    /**
     * Handle tag team reinstated events.
     */
    public function handleTagTeamSuspended(TagTeamSuspended $event): void
    {
        $event->tagTeam->currentWrestlers
            ->each(fn ($wrestler) => SuspendAction::run($wrestler, $event->suspensionDate));
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            TagTeamSuspended::class,
            [SuspendedTagTeamSubscriber::class, 'handleTagTeamSuspended']
        );
    }
}
