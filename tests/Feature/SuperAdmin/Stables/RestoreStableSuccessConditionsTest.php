<?php

namespace Tests\Feature\SuperAdmin\Stables;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\StableFactory;
use Tests\TestCase;

/**
 * @group stables
 * @group superadmins
 * @group roster
 */
class RestoreStableSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_restore_a_deleted_stable()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $stable = StableFactory::new()->softDeleted()->create();

        $response = $this->restoreRequest($stable);

        $response->assertRedirect(route('stables.index'));
        $this->assertNull($stable->fresh()->deleted_at);
    }
}
