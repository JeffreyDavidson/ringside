<?php

namespace App\Actions\Referees;

use App\Models\Referee;
use Lorisleiva\Actions\Concerns\AsAction;

class EmployAction extends BaseRefereeAction
{
    use AsAction;

    /**
     * Employ a referee.
     *
     * @param  \App\Models\Referee  $referee
     * @param  \Carbon\Carbon|null  $startDate
     *
     * @return void
     */
    public function handle(Referee $referee, $startDate = null): void
    {
        $startDate ??= now();

        $this->refereeRepository->employ($referee, $startDate);
        $referee->save();
    }
}
