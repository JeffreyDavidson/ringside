<?php

namespace Tests\Feature\Admin\Referees;

use App\Models\Referee;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $this->actAs('administrator');
        $referee = factory(Referee::class)->create();
        $referee->delete();

        $response = $this->restoreRequest($referee);

        $response->assertRedirect(route('referees.index'));
        $this->assertNull($referee->fresh()->deleted_at);
    }
}
