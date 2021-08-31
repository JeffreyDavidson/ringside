<?php

namespace Tests\Factories;

use App\Models\TagTeam;
use App\Models\Wrestler;

class TagTeamRequestDataFactory
{
    private string $name = 'Example Tag Team Name';
    private string $signature_move = 'The Signature Move';
    private string $started_at = '2021-01-01 00:00:00';

    public static function new(): self
    {
        return new self();
    }

    public function create(array $overrides = []): array
    {
        $wrestlers = Wrestler::factory()->count(2)->create();

        return array_replace([
            'name' => $this->name,
            'signature_move' => $this->signature_move,
            'started_at' => $this->started_at,
            'wrestlers' => $overrides['wrestlers'] ?? $wrestlers->pluck('id')->toArray(),
        ], $overrides);
    }

    public function withTagTeam(TagTeam $tagTeam): self
    {
        $clone = clone $this;

        $this->name = $tagTeam->name;
        $this->signature_move = $tagTeam->signature_move;

        return $clone;
    }
}
