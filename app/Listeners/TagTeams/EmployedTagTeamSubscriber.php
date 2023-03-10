<?php

namespace App\Listeners\TagTeams;

use App\Actions\Wrestlers\EmployAction;
use App\Events\TagTeams\TagTeamEmployed;
use Illuminate\Events\Dispatcher;

class EmployedTagTeamSubscriber
{
    /**
     * Handle tag team reinstated events.
     */
    public function handleTagTeamEmployed(TagTeamEmployed $event): void
    {
        $event->tagTeam->currentWrestlers
            ->reject(fn ($wrestler) => $wrestler->isCurrentlyEmployed())
            ->each(fn ($wrestler) => EmployAction::run($wrestler, $event->employmentDate));
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            TagTeamEmployed::class,
            [EmployedTagTeamSubscriber::class, 'handleTagTeamEmployed']
        );
    }
}
