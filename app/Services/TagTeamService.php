<?php

namespace App\Services;

use App\Exceptions\NotEnoughMembersException;
use App\Models\TagTeam;
use App\Repositories\TagTeamRepository;
use App\Strategies\Employment\TagTeamEmploymentStrategy;
use App\Strategies\Employment\WrestlerEmploymentStrategy;
use App\Strategies\Reinstate\TagTeamReinstateStrategy;
use App\Strategies\Release\TagTeamReleaseStrategy;
use App\Strategies\Retirement\TagTeamRetirementStrategy;
use App\Strategies\Suspend\TagTeamSuspendStrategy;
use App\Strategies\Unretire\TagTeamUnretireStrategy;
use Exception;

class TagTeamService
{
    /**
     * The repository implementation.
     *
     * @var \App\Repositories\TagTeamRepository
     */
    protected $tagTeamRepository;

    /**
     * Create a new tag team service instance.
     *
     * @param \App\Repositories\TagTeamRepository $tagTeamRepository
     */
    public function __construct(TagTeamRepository $tagTeamRepository)
    {
        $this->tagTeamRepository = $tagTeamRepository;
    }

    /**
     * Create a tag team with given data.
     *
     * @param  array $data
     * @return \App\Models\TagTeam $tagTeam
     */
    public function create(array $data)
    {
        $tagTeam = $this->tagTeamRepository->create($data);

        $this->addTagTeamPartners($tagTeam, $data['wrestlers']);

        if ($data['started_at']) {
            (new TagTeamEmploymentStrategy($tagTeam))->employ($data['started_at']);
        }

        return $tagTeam;
    }

    /**
     * Update a given tag team with given data.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @param  array $data
     * @return \App\Models\TagTeam $tagTeam
     */
    public function update(TagTeam $tagTeam, array $data)
    {
        $this->tagTeamRepository->update($tagTeam, $data);

        $this->updateTagTeamPartners($tagTeam, $data['wrestlers']);

        if ($data['started_at'] && ! $tagTeam->isCurrentlyEmployed()) {
            $tagTeam->employ($data['started_at']);
        }

        return $tagTeam;
    }

    /**
     * Delete a given tag team.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @return void
     */
    public function delete(TagTeam $tagTeam)
    {
        $this->tagTeamRepository->delete($tagTeam);
    }

    /**
     * Restore a given tag team.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @return void
     */
    public function restore(TagTeam $tagTeam)
    {
        $this->tagTeamRepository->restore($tagTeam);
    }

    /**
     * Add given wrestlers to a given tag team.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @param  array $wrestlerIds
     * @return \App\Models\TagTeam
     */
    public function addTagTeamPartners(TagTeam $tagTeam, array $wrestlerIds)
    {
        if ($wrestlerIds) {
            $tagTeam->addWrestlers($wrestlerIds, now());
        }

        return $tagTeam;
    }

    /**
     * Add given wrestlers to a given tag team on a given join date.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @param  array  $wrestlers
     * @param  string|null $joinDate
     *
     * @throws Exception
     *
     * @return $this
     */
    public function addWrestlers($tagTeam, $wrestlerIds, $joinDate = null)
    {
        if (count($wrestlerIds) !== self::MAX_WRESTLERS_COUNT) {
            throw NotEnoughMembersException::forTagTeam();
        }

        $joinDate ??= now()->toDateTimeString();

        $tagTeam->wrestlers()->sync([
            $wrestlerIds[0] => ['joined_at' => $joinDate],
            $wrestlerIds[1] => ['joined_at' => $joinDate],
        ]);

        return $this;
    }

    /**
     * Update a given tag team with given wrestlers.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @param  array $wrestlerIds
     * @return \App\Models\TagTeam $tagTeam
     */
    public function updateTagTeamPartners(TagTeam $tagTeam, array $wrestlerIds)
    {
        if ($tagTeam->currentWrestlers->isEmpty()) {
            if ($wrestlerIds) {
                foreach ($wrestlerIds as $wrestlerId) {
                    $tagTeam->currentWrestlers()->attach($wrestlerId, ['joined_at' => now()]);
                }
            }
        } else {
            $currentTagTeamPartners = collect($tagTeam->currentWrestlers->modelKeys());
            $suggestedTagTeamPartners = collect($wrestlerIds);
            $formerTagTeamPartners = $currentTagTeamPartners->diff($suggestedTagTeamPartners);
            $newTagTeamPartners = $suggestedTagTeamPartners->diff($currentTagTeamPartners);

            $now = now();

            foreach ($formerTagTeamPartners as $tagTeamPartner) {
                $tagTeam->currentWrestlers()->updateExistingPivot($tagTeamPartner, ['left_at' => $now]);
            }

            foreach ($newTagTeamPartners as $newTagTeamPartner) {
                $tagTeam->currentWrestlers()->attach(
                    $newTagTeamPartner,
                    ['joined_at' => $now]
                );
            }
        }

        return $tagTeam;
    }

    /**
     * Employ a given tag team.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @return void
     */
    public function employ(TagTeam $tagTeam)
    {
        (new TagTeamEmploymentStrategy($tagTeam))->employ();

        if ($tagTeam->currentWrestlers->every->isNotInEmployment()) {
            foreach ($tagTeam->currentWrestlers as $wrestler) {
                (new WrestlerEmploymentStrategy($wrestler))->employ($tagTeam->currentEmployment->started_at);
            }
        }
    }

    /**
     * Release a given tag team.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @return void
     */
    public function release(TagTeam $tagTeam)
    {
        (new TagTeamReleaseStrategy($tagTeam))->release();
    }

    /**
     * Suspend a given tag team.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @return void
     */
    public function suspend(TagTeam $tagTeam)
    {
        (new TagTeamSuspendStrategy($tagTeam))->suspend();
    }

    /**
     * Reinstate a given tag team.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @return void
     */
    public function reinstate(TagTeam $tagTeam)
    {
        (new TagTeamReinstateStrategy($tagTeam))->reinstate();
    }

    /**
     * Retire a given tag team.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @return void
     */
    public function retire(TagTeam $tagTeam)
    {
        (new TagTeamRetirementStrategy($tagTeam))->retire();
    }

    /**
     * Unretire a given tag team.
     *
     * @param  \App\Models\TagTeam $tagTeam
     * @return void
     */
    public function unretire(TagTeam $tagTeam)
    {
        (new TagTeamUnretireStrategy($tagTeam))->unretire();
    }
}
