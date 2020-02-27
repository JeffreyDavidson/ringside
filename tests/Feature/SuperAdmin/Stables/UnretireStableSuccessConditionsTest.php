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
class UnretireStableSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_unretire_a_retired_stable()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $stable = StableFactory::new()->retired()->create();

        $response = $this->unretireRequest($stable);

        $response->assertRedirect(route('stables.index'));
        $this->assertEquals(now()->toDateTimeString(), $stable->fresh()->retirements()->latest()->first()->ended_at);
    }
}
