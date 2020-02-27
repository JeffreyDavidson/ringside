<?php

namespace Tests\Feature\Admin\Managers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\ManagerFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group admins
 * @group roster
 */
class RestoreManagerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_restore_a_deleted_manager()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->softDeleted()->create();

        $response = $this->restoreRequest($manager);

        $response->assertRedirect(route('managers.index'));
        $this->assertNull($manager->fresh()->deleted_at);
    }
}
