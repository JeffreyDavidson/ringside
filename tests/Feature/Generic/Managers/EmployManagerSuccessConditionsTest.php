<?php

namespace Tests\Feature\Generic\Managers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\ManagerFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group generics
 * @group roster
 */
class EmployManagerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_manager_without_a_current_employment_can_be_employed()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->create();

        $response = $this->employRequest($manager);

        $response->assertRedirect(route('managers.index'));
        tap($manager->fresh(), function ($manager) {
            $this->assertTrue($manager->currentEmployment()->exists());
        });
    }
}
