<?php

namespace Tests\Feature\SuperAdmin\Managers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\ManagerFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group superadmins
 * @group roster
 */
class InjureManagerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_injure_an_available_manager()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $manager = ManagerFactory::new()->available()->create();

        $response = $this->injureRequest($manager);

        $response->assertRedirect(route('managers.index'));
        $this->assertEquals(now()->toDateTimeString(), $manager->fresh()->currentInjury->started_at);
    }
}
