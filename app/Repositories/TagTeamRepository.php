<?php

namespace App\Repositories;

use App\DataTransferObjects\TagTeamData;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TagTeamRepository
{
    /**
     * Create a new tag team with the given data.
     *
     * @param  \App\DataTransferObjects\TagTeamData $tagTeamData
     *
     * @return \App\Models\TagTeam
     */
    public function create(TagTeamData $tagTeamData)
    {
        return TagTeam::create([
            'name' => $tagTeamData->name,
            'signature_move' => $tagTeamData->signature_move,
        ]);
    }

    /**
     * Update a given tag team with the given data.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @param  \App\DataTransferObjects\TagTeamData $tagTeamData
     *
     * @return \App\Models\TagTeam $tagTeam
     */
    public function update(TagTeam $tagTeam, TagTeamData $tagTeamData)
    {
        $tagTeam->update([
            'name' => $tagTeamData->name,
            'signature_move' => $tagTeamData->signature_move,
        ]);

        return $tagTeam;
    }

    /**
     * Delete a given tag team.
     *
     * @param  \App\Models\TagTeam $tagTeam
     *
     * @return void
     */
    public function delete(TagTeam $tagTeam)
    {
        $tagTeam->delete();
    }

    /**
     * Restore a given tag team.
     *
     * @param  \App\Models\TagTeam $tagTeam
     *
     * @return void
     */
    public function restore(TagTeam $tagTeam)
    {
        $tagTeam->restore();
    }

    /**
     * Employ a given tag team on a given date.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @param  \Carbon\Carbon $employmentDate
     *
     * @return \App\Models\TagTeam $tagTeam
     */
    public function employ(TagTeam $tagTeam, Carbon $employmentDate)
    {
        $tagTeam->employments()->updateOrCreate(
            ['ended_at' => null],
            ['started_at' => $employmentDate->toDateTimeString()]
        );

        return $tagTeam;
    }

    /**
     * Release a given tag team on a given date.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @param  \Carbon\Carbon $releaseDate
     *
     * @return \App\Models\TagTeam $tagTeam
     */
    public function release(TagTeam $tagTeam, Carbon $releaseDate)
    {
        $tagTeam->currentEmployment()->update(['ended_at' => $releaseDate->toDateTimeString()]);

        return $tagTeam;
    }

    /**
     * Retire a given tag team on a given date.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @param  \Carbon\Carbon $retirementDate
     *
     * @return \App\Models\TagTeam $tagTeam
     */
    public function retire(TagTeam $tagTeam, Carbon $retirementDate)
    {
        $tagTeam->retirements()->create(['started_at' => $retirementDate->toDateTimeString()]);

        return $tagTeam;
    }

    /**
     * Unretire a given tag team on a given date.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @param  \Carbon\Carbon $unretireDate
     *
     * @return \App\Models\TagTeam $tagTeam
     */
    public function unretire(TagTeam $tagTeam, Carbon $unretireDate)
    {
        $tagTeam->currentRetirement()->update(['ended_at' => $unretireDate->toDateTimeString()]);

        return $tagTeam;
    }

    /**
     * Suspend a given tag team on a given date.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @param  \Carbon\Carbon $suspensionDate
     *
     * @return \App\Models\TagTeam $tagTeam
     */
    public function suspend(TagTeam $tagTeam, Carbon $suspensionDate)
    {
        $tagTeam->suspensions()->create(['started_at' => $suspensionDate->toDateTimeString()]);

        return $tagTeam;
    }

    /**
     * Reinstate a given tag team on a given date.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @param  \Carbon\Carbon $reinstateDate
     *
     * @return \App\Models\TagTeam $tagTeam
     */
    public function reinstate(TagTeam $tagTeam, Carbon $reinstateDate)
    {
        $tagTeam->currentSuspension()->update(['ended_at' => $reinstateDate->toDateTimeString()]);

        return $tagTeam;
    }

    /**
     * Get the model's first employment date.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @param  \Carbon\Carbon $employmentDate
     *
     * @return \App\Models\TagTeam $tagTeam
     */
    public function updateEmployment(TagTeam $tagTeam, Carbon $employmentDate)
    {
        $tagTeam->futureEmployment()->update(['started_at' => $employmentDate->toDateTimeString()]);

        return $tagTeam;
    }

    /**
     * Add wrestlers to a tag team.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @param  \Illuminate\Support\Collection $wrestlers
     * @param  \Carbon\Carbon|null $joinDate
     *
     * @return \App\Models\TagTeam $tagTeam
     */
    public function addWrestlers(TagTeam $tagTeam, Collection $wrestlers, Carbon $joinDate = null)
    {
        $joinDate ??= now();

        $wrestlers->each(
            fn (Wrestler $wrestler) => $tagTeam->wrestlers()->attach(
                $wrestler->id,
                ['joined_at' => $joinDate->toDateTimeString()]
            )
        );

        return $tagTeam;
    }

    /**
     * Add wrestlers to a tag team.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @param  \Illuminate\Support\Collection $formerTagTeamPartners
     * @param  \Illuminate\Support\Collection $newTagTeamPartners
     * @param  \Carbon\Carbon|null $date
     *
     * @return \App\Models\TagTeam $tagTeam
     */
    public function syncTagTeamPartners(
        TagTeam $tagTeam,
        Collection $formerTagTeamPartners,
        Collection $newTagTeamPartners,
        Carbon $date = null
    ) {
        $date ??= now();

        $formerTagTeamPartners->each(
            fn (Wrestler $formerTagTeamPartner) => $tagTeam->currentWrestlers()->updateExistingPivot(
                $formerTagTeamPartner,
                ['left_at' => $date->toDateTimeString()]
            )
        );

        $newTagTeamPartners->each(
            fn (Wrestler $newTagTeamPartner) => $tagTeam->currentWrestlers()->updateExistingPivot(
                $newTagTeamPartner,
                ['joined_at' => $date->toDateTimeString()]
            )
        );

        return $tagTeam;
    }
}
