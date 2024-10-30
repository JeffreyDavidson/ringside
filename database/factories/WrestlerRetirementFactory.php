<?php

namespace Database\Factories;

use App\Models\Wrestler;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WrestlerRetirement>
 */
class WrestlerRetirementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wrestler_id' => Wrestler::factory(),
            'started_at' => now()->toDateTimeString()
        ];
    }
}
