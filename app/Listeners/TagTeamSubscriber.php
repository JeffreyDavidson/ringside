<?php

namespace App\Listeners;

use App\Actions\TagTeams\EmployAction;
use App\Actions\TagTeams\RemoveTagTeamPartnerAction;
use App\Actions\Wrestlers\EmployAction as EmployWrestler;
use App\Actions\Wrestlers\ReinstateAction;
use App\Actions\Wrestlers\ReleaseAction;
use App\Actions\Wrestlers\RetireAction;
use App\Actions\Wrestlers\SuspendAction;
use App\Actions\Wrestlers\UnretireAction;
use App\Events\TagTeams\TagTeamDeleted;
use App\Events\TagTeams\TagTeamEmployed;
use App\Events\TagTeams\TagTeamReinstated;
use App\Events\TagTeams\TagTeamReleased;
use App\Events\TagTeams\TagTeamRetired;
use App\Events\TagTeams\TagTeamSuspended;
use App\Events\TagTeams\TagTeamUnretired;
use Illuminate\Events\Dispatcher;

class TagTeamSubscriber
{
    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            TagTeamDeleted::class,
            [$this::class, 'removeTagTeamPartners']
        );

        $events->listen(
            TagTeamUnretired::class,
            [$this::class, 'unretireTagTeamPartners']
        );

        $events->listen(
            TagTeamSuspended::class,
            [$this::class, 'suspendTagTeamPartners']
        );

        $events->listen(
            TagTeamRetired::class,
            [$this::class, 'retireTagTeamPartners']
        );

        $events->listen(
            TagTeamReleased::class,
            [$this::class, 'releaseTagTeamPartners']
        );

        $events->listen(
            TagTeamReinstated::class,
            [$this::class, 'reinstateTagTeamPartners']
        );

        $events->listen(
            TagTeamEmployed::class,
            [$this::class, 'employTagTeamPartners']
        );
    }

    public function removeTagTeamPartners(TagTeamDeleted $event): void
    {
        $now = now();

        $event->tagTeam->currentWrestlers
            ->each(fn ($wrestler) => RemoveTagTeamPartnerAction::run($event->tagTeam, $wrestler, $now));
    }

    public function suspendTagTeamPartners(TagTeamSuspended $event): void
    {
        $event->tagTeam->currentWrestlers
            ->each(fn ($wrestler) => SuspendAction::run($wrestler, $event->suspensionDate));
    }

    public function unretireTagTeamPartners(TagTeamUnretired $event): void
    {
        $event->tagTeam->currentWrestlers
            ->each(fn ($wrestler) => UnretireAction::run($wrestler, $event->unretireDate));

        app(EmployAction::class)->run($event->tagTeam, $event->unretireDate);
    }

    public function retireTagTeamPartners(TagTeamRetired $event): void
    {
        $event->tagTeam->currentWrestlers
            ->each(fn ($wrestler) => RetireAction::run($wrestler, $event->retirementDate));
    }

    public function releaseTagTeamPartners(TagTeamReleased $event): void
    {
        $event->tagTeam->currentWrestlers
            ->each(fn ($wrestler) => ReleaseAction::run($wrestler, $event->releaseDate));
    }

    public function reinstateTagTeamPartners(TagTeamReinstated $event): void
    {
        $event->tagTeam->currentWrestlers
            ->each(fn ($wrestler) => ReinstateAction::run($wrestler, $event->reinstatementDate));
    }

    public function employTagTeamPartners(TagTeamEmployed $event): void
    {
        $event->tagTeam->currentWrestlers
            ->reject(fn ($wrestler) => $wrestler->isCurrentlyEmployed())
            ->each(fn ($wrestler) => EmployWrestler::run($wrestler, $event->employmentDate));
    }
}
