<?php

declare(strict_types=1);

namespace App\Actions\Wrestlers;

use App\Exceptions\CannotBeInjuredException;
use App\Models\Wrestler;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class InjureAction extends BaseWrestlerAction
{
    use AsAction;

    /**
     * Injure a wrestler.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @param  \Illuminate\Support\Carbon|null  $injureDate
     * @return void
     */
    public function handle(Wrestler $wrestler, ?Carbon $injureDate = null): void
    {
        throw_unless($wrestler->canBeInjured(), CannotBeInjuredException::class);

        $injureDate ??= now();

        $this->wrestlerRepository->injure($wrestler, $injureDate);

        if ($wrestler->isAMemberOfCurrentTagTeam()) {
            $wrestler->currentTagTeam->save();
        }
    }
}
