<?php

namespace App\Listeners\TagTeams;

use App\Actions\TagTeams\RemoveTagTeamPartnerAction;
use App\Events\TagTeams\TagTeamDeleted;
use Illuminate\Events\Dispatcher;

class DeletedTagTeamSubscriber
{
    /**
     * Handle tag team reinstated events.
     */
    public function handleTagTeamDeleted(TagTeamDeleted $event): void
    {
        $now = now();

        $event->tagTeam->currentWrestlers
            ->each(fn ($wrestler) => RemoveTagTeamPartnerAction::run($wrestler, $now));
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            TagTeamDeleted::class,
            [DeletedTagTeamSubscriber::class, 'handleTagTeamDeleted']
        );
    }
}
