<?php

namespace App\Listeners\Wrestlers;

use App\Enums\TagTeamStatus;
use App\Events\Wrestlers\WrestlerReinstated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Events\Dispatcher;

class ReinstatedWrestlerSubscriber
{
    /**
     * Handle tag team wrestler reinstated events.
     */
    public function handleTagTeamWrestlerReinstated(WrestlerReinstated $event): void
    {
        if ($event->wrestler->isAMemberOfACurrentTagTeam()) {
            $event->wrestler->currentTagTeam->update(['status' => TagTeamStatus::BOOKABLE]);
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            WrestlerReinstated::class,
            [ReinstatedWrestlerSubscriber::class, 'handleTagTeamWrestlerReinstated']
        );
    }
}
