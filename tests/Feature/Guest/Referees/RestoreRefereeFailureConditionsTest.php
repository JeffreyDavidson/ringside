<?php

namespace Tests\Feature\Guest\Referees;

use Illuminate\Foundation\Testing\RefreshDatabase;
use RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group guests
 * @group roster
 */
class RestoreRefereeFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_restore_a_deleted_referee()
    {
        $referee = RefereeFactory::new()->softDeleted()->create();

        $response = $this->restoreRequest($referee);

        $response->assertRedirect(route('login'));
    }
}
