<?php

namespace App\Listeners\TagTeams;

use App\Actions\Wrestlers\ReleaseAction;
use App\Events\TagTeams\TagTeamReleased;
use Illuminate\Events\Dispatcher;

class ReleasedTagTeamSubscriber
{
    /**
     * Handle tag team wrestler released events.
     */
    public function handleTagTeamReleased(TagTeamReleased $event): void
    {
        $event->tagTeam->currentWrestlers->each(fn ($wrestler) => ReleaseAction::run($wrestler, $event->releaseDate));
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            TagTeamReleased::class,
            [ReleasedTagTeamSubscriber::class, 'handleTagTeamReleased']
        );
    }
}
