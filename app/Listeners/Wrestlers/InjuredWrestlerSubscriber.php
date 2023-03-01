<?php

namespace App\Listeners\Wrestlers;

use App\Enums\TagTeamStatus;
use App\Events\Wrestlers\WrestlerInjured;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Events\Dispatcher;

class InjuredWrestlerSubscriber
{
    /**
     * Handle tag team wrestler injured events.
     */
    public function handleTagTeamWrestlerInjured(WrestlerInjured $event): void
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
            WrestlerInjured::class,
            [InjuredWrestlerSubscriber::class, 'handleTagTeamWrestlerInjured']
        );
    }
}
