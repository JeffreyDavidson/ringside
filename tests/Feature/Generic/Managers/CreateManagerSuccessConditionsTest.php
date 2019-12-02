<?php

namespace Tests\Feature\Generic\Manager;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group generics
 * @group roster
 */
class CreateManagerSuccessConditionsTest extends TestCase
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
    public function a_manager_started_today_or_before_is_bookable()
    {
        $this->actAs('administrator');

        $this->storeRequest('manager', $this->validParams(['started_at' => today()->toDateTimeString()]));

        tap(Manager::first(), function ($manager) {
            $this->assertEquals('bookable', $manager->status);
        });
    }

    /** @test */
    public function a_manager_started_after_today_is_pending_employment()
    {
        $this->actAs('administrator');

        $this->storeRequest('manager', $this->validParams(['started_at' => Carbon::tomorrow()->toDateTimeString()]));

        tap(Manager::first(), function ($manager) {
            $this->assertEquals('pending-employment', $manager->status);
        });
    }

    /** @test */
    public function a_manager_started_at_date_is_optional()
    {
        $this->actAs('administrator');

        $response = $this->storeRequest('manager', $this->validParams(['started_at' => '']));

        $response->assertSessionDoesntHaveErrors('started_at');
    }

    /** @test */
    public function a_manager_can_be_created()
    {
        $now = now();
        Carbon::setTestNow($now);

        $this->actAs('administrator');

        $response = $this->storeRequest('manager', $this->validParams());

        $response->assertRedirect(route('managers.index'));
        tap(Manager::first(), function ($manager) {
            $this->assertEquals('John', $manager->first_name);
            $this->assertEquals('Smith', $manager->last_name);
        });
    }

    /** @test */
    public function a_manager_can_be_employed_during_creation()
    {
        $now = now();
        Carbon::setTestNow($now);

        $this->actAs('administrator');

        $this->storeRequest('manager', $this->validParams(['started_at' => $now->toDateTimeString()]));

        tap(Manager::first(), function ($manager) {
            $this->assertTrue($manager->isEmployed());
        });
    }

    /** @test */
    public function a_manager_can_be_created_without_employing()
    {
        $this->actAs('administrator');

        $this->storeRequest('manager', $this->validParams(['started_at' => null]));

        tap(Manager::first(), function ($manager) {
            $this->assertFalse($manager->isEmployed());
        });
    }
}
