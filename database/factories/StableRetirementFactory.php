<?php

namespace Database\Factories;

use App\Models\Stable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StableRetirement>
 */
class StableRetirementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'stable_id' => Stable::factory(),
            'started_at' => now()->toDateTimeString()
        ];
    }
}
