<?php

namespace Database\Factories;

use App\Models\TagTeam;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TagTeamRetirement>
 */
class TagTeamRetirementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tag_team_id' => TagTeam::factory(),
            'started_at' => now()->toDateTimeString()
        ];
    }
}
