<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Event;
use App\Models\EventMatch;
use App\Models\MatchType;
use App\Models\TagTeam;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventMatch>
 */
class EventMatchFactory extends Factory
{
    public function withReferees($referees)
    {
        $this->hasAttached($referees);
    }

    public function withTitles($titles)
    {
        $this->hasAttached($titles);
    }

    public function withCompetitors($competitors)
    {
        $this->hasAttached($competitors, ['side_number' => 0]);
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'match_type_id' => MatchType::factory(),
            'preview' => null,
        ];
    }

    public function tagTeamMatch()
    {
        return $this
            ->state(function () {
                return ['match_type_id' => MatchType::where('slug', 'tagteam')->first()->id];
            })
            ->hasAttached(TagTeam::factory(), ['side_number' => 0])
            ->hasAttached(TagTeam::factory(), ['side_number' => 1]);
    }
}
