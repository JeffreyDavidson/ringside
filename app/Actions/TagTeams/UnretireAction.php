<?php

declare(strict_types=1);

namespace App\Actions\TagTeams;

use App\Events\TagTeams\TagTeamUnretired;
use App\Exceptions\CannotBeUnretiredException;
use App\Models\TagTeam;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class UnretireAction extends BaseTagTeamAction
{
    use AsAction;

    /**
     * Unretire a tag team.
     */
    public function handle(TagTeam $tagTeam, ?Carbon $unretiredDate = null): void
    {
        $this->ensureCanBeUnretired($tagTeam);

        $unretiredDate ??= now();

        $this->tagTeamRepository->unretire($tagTeam, $unretiredDate);

        event(new TagTeamUnretired($tagTeam, $unretiredDate));
    }

    /**
     * Ensure a tag team can be unretired.
     *
     * @throws \App\Exceptions\CannotBeUnretiredException
     */
    private function ensureCanBeUnretired(TagTeam $tagTeam): void
    {
        if (! $tagTeam->isRetired()) {
            throw CannotBeUnretiredException::notRetired($tagTeam);
        }
    }
}
