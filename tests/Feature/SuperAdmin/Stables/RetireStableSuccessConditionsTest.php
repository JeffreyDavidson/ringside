<?php

namespace Tests\Feature\SuperAdmin\Stables;

use App\Enums\Role;
use Tests\TestCase;
use App\Models\Stable;
use Tests\Factories\StableFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group stables
 * @group superadmins
 * @group roster
 */
class RetireStableSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_retire_a_bookable_stable()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $stable = StableFactory::new()->active()->create();

        $response = $this->retireRequest($stable);

        $response->assertRedirect(route('stables.index'));
        tap($stable->fresh(), function ($stable) {
            $this->assertEquals(now()->toDateTimeString(), $stable->currentRetirement->started_at->toDateTimeString()
            );
        });
    }
}
