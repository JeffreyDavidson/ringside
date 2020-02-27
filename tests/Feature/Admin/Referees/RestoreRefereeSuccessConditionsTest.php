<?php

namespace Tests\Feature\Admin\Referees;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group admins
 * @group roster
 */
class RestoreRefereeSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_restore_a_deleted_referee()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->softDeleted()->create();

        $response = $this->restoreRequest($referee);

        $response->assertRedirect(route('referees.index'));
        $this->assertNull($referee->fresh()->deleted_at);
    }
}
