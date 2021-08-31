<?php

namespace Tests\Factories;

use App\Models\Title;

class TitleRequestDataFactory
{
    private string $name = 'Example Title Name';
    private string $activated_at = '2021-01-01 00:00:00';

    public static function new(): self
    {
        return new self();
    }

    public function create(array $overrides = []): array
    {
        return array_replace([
            'name' => $this->name,
            'activated_at' => $this->activated_at,
        ], $overrides);
    }

    public function withTitle(Title $title): self
    {
        $clone = clone $this;

        $this->name = $title->name;

        return $clone;
    }
}
