<?php

namespace Tests\Feature\Generic\Managers;

use App\Enums\Role;
use App\Models\Manager;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
    public function a_manager_started_at_date_is_optional()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('manager', $this->validParams(['started_at' => '']));

        $response->assertSessionDoesntHaveErrors('started_at');
    }

    /** @test */
    public function a_manager_can_be_created()
    {
        $now = now();
        Carbon::setTestNow($now);

        $this->actAs(Role::ADMINISTRATOR);

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

        $this->actAs(Role::ADMINISTRATOR);

        $this->storeRequest('manager', $this->validParams(['started_at' => $now->toDateTimeString()]));

        tap(Manager::first(), function ($manager) {
            $this->assertTrue($manager->isCurrentlyEmployed());
        });
    }

    /** @test */
    public function a_manager_can_be_created_without_employing()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $this->storeRequest('manager', $this->validParams(['started_at' => null]));

        tap(Manager::first(), function ($manager) {
            $this->assertFalse($manager->isCurrentlyEmployed());
        });
    }
}
