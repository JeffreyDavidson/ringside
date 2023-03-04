<?php

namespace App\Listeners\TagTeams;

use App\Actions\TagTeams\EmployAction;
use App\Actions\Wrestlers\UnretireAction;
use App\Events\TagTeams\TagTeamUnretired;
use Illuminate\Events\Dispatcher;

class UnretiredTagTeamSubscriber
{
    /**
     * Handle tag team unretired events.
     */
    public function handleTagTeamUnretired(TagTeamUnretired $event): void
    {
        $event->tagTeam->currentWrestlers
            ->each(fn ($wrestler) => UnretireAction::run($wrestler, $event->unretireDate));

        app(EmployAction::class)->run($event->tagTeam, $event->unretireDate);
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            TagTeamUnretired::class,
            [UnretiredTagTeamSubscriber::class, 'handleTagTeamUnretired']
        );
    }
}
