<?php

namespace App\Listeners\Wrestlers;

use App\Enums\TagTeamStatus;
use App\Events\Wrestlers\WrestlerRetired;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Events\Dispatcher;

class RetiredWrestlerSubscriber
{
    /**
     * Handle tag team wrestler retired events.
     */
    public function handleTagTeamWrestlerRetired(WrestlerRetired $event): void
    {
        if ($event->wrestler->isAMemberOfACurrentTagTeam()) {
            $event->wrestler->currentTagTeam->update(['status' => TagTeamStatus::UNBOOKABLE]);
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
            WrestlerRetired::class,
            [RetiredWrestlerSubscriber::class, 'handleTagTeamWrestlerRetired']
        );
    }
}
