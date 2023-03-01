<?php

namespace App\Listeners\Wrestlers;

use App\Enums\TagTeamStatus;
use App\Events\Wrestlers\WrestlerReleased;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Events\Dispatcher;

class ReleasedWrestlerSubscriber
{
    /**
     * Handle tag team wrestler released events.
     */
    public function handleTagTeamWrestlerReleased(WrestlerReleased $event): void
    {
        if ($event->wrestler->isAMemberOfACurrentTagTeam()) {
            $event->wrestler->currentTagTeam->update(['status' => TagTeamStatus::UNBOOKABLE]);
            // $this->wrestlerRepository->removeFromCurrentTagTeam($event->wrestler, $releaseDate);
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
            WrestlerReleased::class,
            [ReleasedWrestlerSubscriber::class, 'handleTagTeamWrestlerReleased']
        );
    }
}
