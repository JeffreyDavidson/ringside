<?php

declare(strict_types=1);

namespace App\Data;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

readonly class StableData
{
    /**
     * Create a new stable data instance.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<int, \App\Models\Wrestler>  $wrestlers
     * @param  \Illuminate\Database\Eloquent\Collection<int, \App\Models\TagTeam>  $tagTeams
     * @param  \Illuminate\Database\Eloquent\Collection<int, \App\Models\Manager>  $managers
     */
    public function __construct(
        public string $name,
        public ?Carbon $start_date,
        public Collection $wrestlers,
        public Collection $tagTeams,
        public Collection $managers,
    ) {}

    public static function fromForm($data): self
    {
        return new self(
            $data['name'],
            $data['start_date'],
            $data['wrestlers'],
            $data['tag_teams'],
            $data['managers'],
        );
    }
}
