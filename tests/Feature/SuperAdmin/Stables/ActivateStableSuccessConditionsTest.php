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
class ActivateStableSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_activate_a_pending_introduction_stable()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $stable = StableFactory::new()->pendingIntroduction()->create();

        $response = $this->introduceRequest($stable);

        $response->assertRedirect(route('stables.index'));
        tap($stable->fresh(), function ($stable) {
            $this->assertTrue($stable->is_bookable);
        });
    }
}
