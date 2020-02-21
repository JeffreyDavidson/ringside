<?php

namespace Tests\Feature\SuperAdmin\Managers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ManagerFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group superadmins
 * @group roster
 */
class UnretireManagerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_unretire_a_retired_manager()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $manager = ManagerFactory::new()->retired()->create();

        $response = $this->unretireRequest($manager);

        $response->assertRedirect(route('managers.index'));
        $this->assertEquals(now()->toDateTimeString(), $manager->fresh()->retirements()->latest()->first()->ended_at);
    }
}
