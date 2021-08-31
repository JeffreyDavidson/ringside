<?php

namespace Tests\Factories;

use App\Models\Referee;

class RefereeRequestDataFactory
{
    private string $first_name = 'James';
    private string $last_name = 'Williams';
    private string $started_at = '2021-01-01 00:00:00';

    public static function new(): self
    {
        return new self();
    }

    public function create(array $overrides = []): array
    {
        return array_replace([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'started_at' => $this->started_at,
        ], $overrides);
    }

    public function withReferee(Referee $referee): self
    {
        $clone = clone $this;

        $this->first_name = $referee->first_name;
        $this->last_name = $referee->last_name;

        return $clone;
    }
}
