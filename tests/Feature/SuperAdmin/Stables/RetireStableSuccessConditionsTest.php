<?php

namespace Tests\Feature\SuperAdmin\Stables;

use Tests\TestCase;
use App\Models\Stable;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group stables
 * @group superadmins
 */
class RetireStableSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_retire_a_bookable_stable()
    {
        $this->actAs('super-administrator');
        $stable = factory(Stable::class)->states('bookable')->create();

        $response = $this->put(route('stables.retire', $stable));

        $response->assertRedirect(route('stables.index'));
        tap($stable->fresh(), function ($stable) {
            $this->assertEquals(
                now()->toDateTimeString(), 
                $stable->retirement->started_at->toDateTimeString()
            );
        });
    }
}
