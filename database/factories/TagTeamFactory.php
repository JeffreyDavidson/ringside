<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TagTeamStatus;
use App\Enums\WrestlerStatus;
use App\Models\Employment;
use App\Models\Retirement;
use App\Models\Suspension;
use App\Models\Wrestler;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TagTeamFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => Str::title($this->faker->words(2, true)),
            'signature_move' => null,
            'status' => TagTeamStatus::UNEMPLOYED,
        ];
    }

    public function bookable()
    {
        $now = now();
        $employmentStartDate = $now->copy()->subDays(3);

        [$wrestlerA, $wrestlerB] = Wrestler::factory()
            ->state(fn () => ['status' => WrestlerStatus::BOOKABLE])
            ->count(2)
            ->has(Employment::factory()->started($employmentStartDate))
            ->create();

        return $this->state(fn () => ['status' => TagTeamStatus::BOOKABLE])
            ->withCurrentWrestlers([$wrestlerA, $wrestlerB])
            ->has(Employment::factory()->started($employmentStartDate));
    }

    public function unbookable()
    {
        $now = now();
        $employmentStartDate = $now->copy()->subDays(3);

        [$wrestlerA, $wrestlerB] = Wrestler::factory()
            ->state(fn () => ['status' => WrestlerStatus::UNEMPLOYED])
            ->count(2)
            ->has(Employment::factory()->started($employmentStartDate))
            ->create();

        return $this->state(fn () => ['status' => TagTeamStatus::UNBOOKABLE])
            ->withCurrentWrestlers([$wrestlerA, $wrestlerB])
            ->has(Employment::factory()->started($employmentStartDate));
    }

    public function withFutureEmployment()
    {
        $employmentStartDate = Carbon::tomorrow();

        [$wrestlerA, $wrestlerB] = Wrestler::factory()
            ->state(fn () => ['status' => WrestlerStatus::FUTURE_EMPLOYMENT])
            ->count(2)
            ->has(Employment::factory()->started($employmentStartDate))
            ->create();

        return $this->state(fn (array $attributes) => ['status' => TagTeamStatus::FUTURE_EMPLOYMENT])
            ->withCurrentWrestlers([$wrestlerA, $wrestlerB])
            ->has(Employment::factory()->started($employmentStartDate));
    }

    public function suspended()
    {
        $now = now();
        $employmentStartDate = $now->copy()->subDays(3);
        $suspensionStartDate = $now->copy()->subDays(2);

        [$wrestlerA, $wrestlerB] = Wrestler::factory()
            ->state(fn () => ['status' => WrestlerStatus::SUSPENDED])
            ->count(2)
            ->has(Employment::factory()->started($employmentStartDate))
            ->has(Suspension::factory()->started($suspensionStartDate))
            ->create();

        return $this->state(fn (array $attributes) => ['status' => TagTeamStatus::SUSPENDED])
            ->withCurrentWrestlers([$wrestlerA, $wrestlerB])
            ->has(Employment::factory()->started($employmentStartDate))
            ->has(Suspension::factory()->started($suspensionStartDate));
    }

    public function retired()
    {
        $now = now();
        $employmentStartDate = $now->copy()->subDays(3);
        $retirementStartDate = $now->copy()->subDays(2);

        [$wrestlerA, $wrestlerB] = Wrestler::factory()
            ->state(fn () => ['status' => WrestlerStatus::RETIRED])
            ->count(2)
            ->has(Employment::factory()->started($employmentStartDate)->ended($retirementStartDate))
            ->has(Retirement::factory()->started($retirementStartDate))
            ->create();

        return $this->state(fn () => ['status' => TagTeamStatus::RETIRED])
            ->withCurrentWrestlers([$wrestlerA, $wrestlerB])
            ->has(Employment::factory()->started($employmentStartDate)->ended($retirementStartDate))
            ->has(Retirement::factory()->started($retirementStartDate));
    }

    public function unemployed()
    {
        [$wrestlerA, $wrestlerB] = Wrestler::factory()
            ->state(fn () => ['status' => WrestlerStatus::UNEMPLOYED])
            ->count(2)
            ->create();

        return $this->state(fn () => ['status' => TagTeamStatus::UNEMPLOYED])
            ->withCurrentWrestlers([$wrestlerA, $wrestlerB]);
    }

    public function released()
    {
        $now = now();
        $employmentStartDate = $now->copy()->subDays(2);
        $employmentEndDate = $now->copy()->subDays(1);

        [$wrestlerA, $wrestlerB] = Wrestler::factory()
            ->state(fn () => ['status' => WrestlerStatus::RELEASED])
            ->count(2)
            ->has(Employment::factory()->started($employmentStartDate)->ended($employmentEndDate))
            ->create();

        return $this->state(fn () => ['status' => TagTeamStatus::RELEASED])
            ->withCurrentWrestlers([$wrestlerA, $wrestlerB])
            ->has(Employment::factory()->started($employmentStartDate)->ended($employmentEndDate));
    }

    public function withCurrentWrestler(Wrestler $wrestler, ?Carbon $joinDate = null)
    {
        $joinDate ??= now();

        return $this->hasAttached($wrestler, [
            'joined_at' => $joinDate,
        ]);
    }

    public function withPreviousWrestler(Wrestler $wrestler, ?Carbon $leftDate = null)
    {
        $leftDate ??= now();

        return $this->hasAttached($wrestler, [
            'joined_at' => $leftDate->subDays(3)->toDateTimeString(),
            'left_at' => $leftDate->toDateTimeString(),
        ]);
    }

    public function withPreviousWrestlers($wrestlers, ?Carbon $datetime = null)
    {
        return collect($wrestlers)->reduce(
            fn ($factory, $wrestler) => $factory->withPreviousWrestler($wrestler, $datetime),
            $this,
        );
    }

    public function withCurrentWrestlers($wrestlers, ?Carbon $datetime = null)
    {
        return collect($wrestlers)->reduce(
            fn ($factory, $wrestler) => $factory->withCurrentWrestler($wrestler, $datetime),
            $this,
        );
    }
}
