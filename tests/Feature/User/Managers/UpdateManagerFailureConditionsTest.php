<?php

namespace Tests\Feature\User\Managers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\ManagerFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group users
 * @group roster
 */
class UpdateManagerFailureConditionsTest extends TestCase
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
    public function a_basic_user_cannot_view_the_form_for_editing_a_manager()
    {
        $this->actAs(Role::BASIC);
        $manager = ManagerFactory::new()->create();

        $response = $this->editRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_update_a_manager()
    {
        $this->actAs(Role::BASIC);
        $manager = ManagerFactory::new()->create();

        $response = $this->updateRequest($manager, $this->validParams());

        $response->assertForbidden();
    }
}
