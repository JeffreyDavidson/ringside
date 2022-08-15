<?php

declare(strict_types=1);

namespace Tests\RequestFactories;

use Worksome\RequestFactories\RequestFactory;

class TagTeamRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'signature_move' => null,
            'start_date' => null,
            'wrestlers' => null,
        ];
    }
}
