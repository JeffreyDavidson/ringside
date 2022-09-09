<?php

declare(strict_types=1);

namespace App\Actions\Wrestlers;

use App\Exceptions\CannotBeUnretiredException;
use App\Models\Wrestler;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class UnretireAction extends BaseWrestlerAction
{
    use AsAction;

    /**
     * Unretire a wrestler.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @param  \Illuminate\Support\Carbon|null  $unretiredDate
     * @return void
     */
    public function handle(Wrestler $wrestler, ?Carbon $unretiredDate = null): void
    {
        throw_unless($wrestler->canBeUnretired(), CannotBeUnretiredException::class);

        $unretiredDate ??= now();

        $this->wrestlerRepository->unretire($wrestler, $unretiredDate);

        EmployAction::run($wrestler, $unretiredDate);
    }
}
