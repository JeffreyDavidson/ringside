<?php

namespace Tests\Feature\Generic\Managers;

use App\Enums\Role;
use Carbon\Carbon;
use Tests\Factories\EmploymentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\ManagerFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group generics
 * @group roster
 */
class UpdateManagerFailureConditionsTest extends TestCase
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
    public function a_manager_started_at_date_is_required_if_employment_start_date_is_in_past()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->employed(
                EmploymentFactory::new()->started(now()->subWeek()->toDateTimeString())
        )->create();

        $response = $this->updateRequest($manager, $this->validParams(['started_at' => '']));

        $response->assertRedirect(route('managers.edit', $manager));
        $response->assertSessionHasErrors('started_at');
        tap($manager->fresh(), function ($manager) {
            $this->assertNotNull($manager->currentEmployment->started_at);
        });
    }

    /** @test */
    public function a_manager_started_at_date_if_filled_cannot_be_after_existing_employment_date_if_date_has_past()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()
            ->employed(
                EmploymentFactory::new()->started(Carbon::yesterday()->toDateTimeString())
            )
            ->create();

        $response = $this->updateRequest($manager, $this->validParams([
            'started_at' => Carbon::tomorrow()->toDateTimeString(),
        ]));

        $response->assertRedirect(route('managers.edit', $manager));
        $response->assertSessionHasErrors('started_at');
    }
}
