<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\EventMatch;
use App\Models\TagTeam;
use App\Models\Title;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TagTeamTitleChampionship>
 */
class TagTeamTitleChampionshipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title_id' => Title::factory(),
            'event_match_id' => EventMatch::factory(),
            'new_champion_id' => TagTeam::factory(),
            'former_champion_id' => TagTeam::factory(),
            'won_at' => Carbon::yesterday(),
            'lost_at' => null,
        ];
    }

    /**
     * Indicate the date the title was won.
     */
    public function wonOn(string $date): static
    {
        return $this->state(['won_at' => $date]);
    }

    /**
     * Indicate the date the title was lost.
     */
    public function lostOn(?string $date): static
    {
        return $this->state(['lost_at' => $date]);
    }
}
