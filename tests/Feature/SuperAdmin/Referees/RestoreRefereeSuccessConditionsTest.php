<?php

namespace Tests\Feature\SuperAdmin\Referees;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group superadmins
 * @group roster
 */
class RestoreRefereeSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_restore_a_deleted_referee()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $referee = RefereeFactory::new()->softDeleted()->create();

        $response = $this->restoreRequest($referee);

        $response->assertRedirect(route('referees.index'));
        $this->assertNull($referee->fresh()->deleted_at);
    }
}
