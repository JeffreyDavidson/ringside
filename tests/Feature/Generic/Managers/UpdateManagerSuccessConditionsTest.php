<?php

namespace Tests\Feature\Generic\Managers;

use App\Enums\Role;
use EmploymentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ManagerFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group generics
 * @group roster
 */
class UpdateManagerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Default attributes for model.
     *
     * @param  array  $overrides
     * @return array
     */
    private function oldAttributes($overrides = [])
    {
        return array_replace([
            'first_name' => 'Bill',
            'last_name' => 'Gates',
        ], $overrides);
    }

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
    public function a_manager_started_at_date_if_not_filled_can_be_changed_if_future_employment_started_at_is_in_future()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()
            ->employed(
                EmploymentFactory::new()->started(now()->addWeek()->toDateTimeString())
            )->create();

        // dd($manager);

        $response = $this->updateRequest($manager, $this->validParams(['started_at' => '']));

        $response->assertSessionDoesntHaveErrors('started_at');
    }

    /** @test */
    public function a_manager_started_at_date_if_filled_can_be_before_existing_employment_date_if_date_is_in_future()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()
            ->employed(
                EmploymentFactory::new()->started(now()->addWeek()->toDateTimeString())
            )
            ->create();

        $response = $this->updateRequest($manager, $this->validParams([
            'started_at' => now()->addDays(2)->toDateTimeString(),
        ]));

        $response->assertSessionDoesntHaveErrors('started_at');
    }
}
