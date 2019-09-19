<?php

namespace Tests\Feature\Guest\Referees;

use App\Models\Referee;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $referee = factory(Referee::class)->create();
        $referee->delete();

        $response = $this->restoreRequest($referee);

        $response->assertRedirect(route('login'));
    }
}
