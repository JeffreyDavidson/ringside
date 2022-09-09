<?php

declare(strict_types=1);

namespace App\Actions\Wrestlers;

use App\Exceptions\CannotBeSuspendedException;
use App\Models\Wrestler;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class SuspendAction extends BaseWrestlerAction
{
    use AsAction;

    /**
     * Suspend a wrestler.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @param  \Illuminate\Support\Carbon|null  $suspensionDate
     * @return void
     */
    public function handle(Wrestler $wrestler, ?Carbon $suspensionDate = null): void
    {
        throw_unless($wrestler->canBeSuspended(), CannotBeSuspendedException::class);

        $suspensionDate ??= now();

        $this->wrestlerRepository->suspend($wrestler, $suspensionDate);

        if ($wrestler->isAMemberOfCurrentTagTeam()) {
            $wrestler->currentTagTeam->save();
        }
    }
}
