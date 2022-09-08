<?php

declare(strict_types=1);

namespace App\Actions\Managers;

use App\Exceptions\CannotBeSuspendedException;
use App\Models\Manager;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class SuspendAction extends BaseManagerAction
{
    use AsAction;

    /**
     * Suspend a manager.
     *
     * @param  \App\Models\Manager  $manager
     * @param  \Illuminate\Support\Carbon|null  $suspensionDate
     * @return void
     */
    public function handle(Manager $manager, ?Carbon $suspensionDate = null): void
    {
        throw_unless($manager->canBeSuspended(), CannotBeSuspendedException::class);

        $suspensionDate ??= now();

        $this->managerRepository->suspend($manager, $suspensionDate);
    }
}
