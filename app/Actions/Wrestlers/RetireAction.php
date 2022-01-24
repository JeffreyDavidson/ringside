<?php

namespace App\Actions\Wrestlers;

use App\Models\Wrestler;
use Lorisleiva\Actions\Concerns\AsAction;

class RetireAction extends BaseWrestlerAction
{
    use AsAction;

    /**
     * Retire a wrestler.
     *
     * @param  \App\Models\Wrestler  $wrestler
     *
     * @return void
     */
    public function handle(Wrestler $wrestler): void
    {
        $retirementDate = now();

        if ($wrestler->isSuspended()) {
            $this->wrestlerRepository->reinstate($wrestler, $retirementDate);
        }

        if ($wrestler->isInjured()) {
            $this->wrestlerRepository->clearInjury($wrestler, $retirementDate);
        }

        $this->wrestlerRepository->release($wrestler, $retirementDate);
        $this->wrestlerRepository->retire($wrestler, $retirementDate);
        $wrestler->save();

        if ($wrestler->currentTagTeam() !== null && $wrestler->currentTagTeam()->exists()) {
            $wrestler->currentTagTeam()->touch();
        }
    }
}
