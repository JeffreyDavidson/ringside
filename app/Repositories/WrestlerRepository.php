<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Data\WrestlerData;
use App\Models\Wrestler;
use Illuminate\Support\Carbon;

class WrestlerRepository
{
    /**
     * Create a new wrestler with the given data.
     */
    public function create(WrestlerData $wrestlerData): Wrestler
    {
        return Wrestler::query()->create([
            'name' => $wrestlerData->name,
            'height' => $wrestlerData->height,
            'weight' => $wrestlerData->weight,
            'hometown' => $wrestlerData->hometown,
            'signature_move' => $wrestlerData->signature_move,
        ]);
    }

    /**
     * Update a given wrestler with given data.
     */
    public function update(Wrestler $wrestler, WrestlerData $wrestlerData): Wrestler
    {
        $wrestler->update([
            'name' => $wrestlerData->name,
            'height' => $wrestlerData->height,
            'weight' => $wrestlerData->weight,
            'hometown' => $wrestlerData->hometown,
            'signature_move' => $wrestlerData->signature_move,
        ]);

        return $wrestler;
    }

    /**
     * Delete a given wrestler.
     */
    public function delete(Wrestler $wrestler): void
    {
        $wrestler->delete();
    }

    /**
     * Restore a given wrestler.
     */
    public function restore(Wrestler $wrestler): void
    {
        $wrestler->restore();
    }

    /**
     * Employ a given wrestler on a given date.
     */
    public function employ(Wrestler $wrestler, Carbon $employmentDate): Wrestler
    {
        $wrestler->employments()->updateOrCreate(
            ['ended_at' => null],
            ['started_at' => $employmentDate->toDateTimeString()]
        );

        return $wrestler;
    }

    /**
     * Release a given wrestler on a given date.
     */
    public function release(Wrestler $wrestler, Carbon $releaseDate): Wrestler
    {
        $wrestler->currentEmployment()->update(['ended_at' => $releaseDate->toDateTimeString()]);

        return $wrestler;
    }

    /**
     * Injure a given wrestler on a given date.
     */
    public function injure(Wrestler $wrestler, Carbon $injureDate): Wrestler
    {
        $wrestler->injuries()->create(['started_at' => $injureDate->toDateTimeString()]);

        return $wrestler;
    }

    /**
     * Clear the injury of a given wrestler on a given date.
     */
    public function clearInjury(Wrestler $wrestler, Carbon $recoveryDate): Wrestler
    {
        $wrestler->currentInjury()->update(['ended_at' => $recoveryDate->toDateTimeString()]);

        return $wrestler;
    }

    /**
     * Retire a given wrestler on a given date.
     */
    public function retire(Wrestler $wrestler, Carbon $retirementDate): Wrestler
    {
        $wrestler->retirements()->create(['started_at' => $retirementDate->toDateTimeString()]);

        return $wrestler;
    }

    /**
     * Unretire a given wrestler on a given date.
     */
    public function unretire(Wrestler $wrestler, Carbon $unretireDate): Wrestler
    {
        $wrestler->currentRetirement()->update(['ended_at' => $unretireDate->toDateTimeString()]);

        return $wrestler;
    }

    /**
     * Suspend a given wrestler on a given date.
     */
    public function suspend(Wrestler $wrestler, Carbon $suspensionDate): Wrestler
    {
        $wrestler->suspensions()->create(['started_at' => $suspensionDate->toDateTimeString()]);

        return $wrestler;
    }

    /**
     * Reinstate a given wrestler on a given date.
     */
    public function reinstate(Wrestler $wrestler, Carbon $reinstateDate): Wrestler
    {
        $wrestler->currentSuspension()->update(['ended_at' => $reinstateDate->toDateTimeString()]);

        return $wrestler;
    }
}
