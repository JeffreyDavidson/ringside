<?php

namespace Tests\Feature\SuperAdmin\Wrestlers;

use App\Models\Wrestler;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group wrestlers
 * @group superadmins
 * @group roster
 */
class SuspendWrestlerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_suspend_a_bookable_wrestler()
    {
        $this->actAs('super-administrator');
        $wrestler = factory(Wrestler::class)->states('bookable')->create();

        $response = $this->suspendRequest($wrestler);

        $response->assertRedirect(route('wrestlers.index'));
        $this->assertEquals(now()->toDateTimeString(), $wrestler->fresh()->suspension->started_at);
    }
}
