<?php

namespace Tests\Feature\Generic\Referees;

use App\Enums\Role;
use App\Models\Referee;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group referees
 * @group generics
 * @group roster
 */
class CreateRefereeSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Valid parameters for request.
     *
     * @param  array $overrides
     * @return array
     */
    private function validParams($overrides = [])
    {
        return array_replace([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'started_at' => now()->toDateTimeString(),
        ], $overrides);
    }

    /** @test */
    public function a_referee_started_at_date_is_optional()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('referee', $this->validParams(['started_at' => null]));

        $response->assertSessionDoesntHaveErrors('started_at');
    }

    /** @test */
    public function a_referee_can_be_created()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('referee', $this->validParams());

        $response->assertRedirect(route('referees.index'));
        tap(Referee::first(), function ($referee) {
            $this->assertEquals('John', $referee->first_name);
            $this->assertEquals('Smith', $referee->last_name);
        });
    }

    /** @test */
    public function a_referee_can_be_employed_during_creation()
    {
        $now = now();
        Carbon::setTestNow($now);

        $this->actAs(Role::ADMINISTRATOR);

        $this->storeRequest('referee', $this->validParams(['started_at' => $now->toDateTimeString()]));

        tap(Referee::first(), function ($referee) {
            $this->assertTrue($referee->isCurrentlyEmployed());
        });
    }

    /** @test */
    public function a_referee_can_be_created_without_employing()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $this->storeRequest('referee', $this->validParams(['started_at' => null]));

        tap(Referee::first(), function ($referee) {
            $this->assertFalse($referee->isCurrentlyEmployed());
        });
    }
}
