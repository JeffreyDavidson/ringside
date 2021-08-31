<?php

namespace Tests\Factories;

use App\Models\Title;
use Carbon\Carbon;

class StableRequestDataFactory
{
    private string $name = 'Example Stable Name';
    private string $started_at = '2021-01-01 00:00:00';

    public static function new(): self
    {
        return new self();
    }

    public function create(array $overrides = []): array
    {
        return array_replace([
            'name' => $this->name,
            'started_at' => $this->started_at,
        ], $overrides);
    }

    public function withStartedAtDate(string | Carbon $startedAt): array
    {
        return array_replace([
            'name' => $this->name,
            'started_at' => $this->started_at,
        ], $overrides);
    }

    public function withTitle(Title $title): self
    {
        $clone = clone $this;

        $this->name = $title->name;

        return $clone;
    }
}
