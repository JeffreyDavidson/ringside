<?php

declare(strict_types=1);

namespace App\Actions\Referees;

use App\Exceptions\CannotBeRetiredException;
use App\Models\Referee;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class RetireAction extends BaseRefereeAction
{
    use AsAction;

    /**
     * Retire a referee.
     *
     * @param  \App\Models\Referee  $referee
     * @param  \Illuminate\Support\Carbon|null  $retirementDate
     * @return void
     */
    public function handle(Referee $referee, ?Carbon $retirementDate = null): void
    {
        throw_unless($referee->canBeRetired(), CannotBeRetiredException::class);

        $retirementDate ??= now();

        if ($referee->isSuspended()) {
            $this->refereeRepository->reinstate($referee, $retirementDate);
        }

        if ($referee->isInjured()) {
            $this->refereeRepository->clearInjury($referee, $retirementDate);
        }

        $this->refereeRepository->release($referee, $retirementDate);
        $this->refereeRepository->retire($referee, $retirementDate);
    }
}
