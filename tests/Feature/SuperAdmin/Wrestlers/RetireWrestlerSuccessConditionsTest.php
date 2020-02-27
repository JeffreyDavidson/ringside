<?php

namespace Tests\Feature\SuperAdmin\Wrestlers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\WrestlerFactory;

/**
 * @group wrestlers
 * @group superadmins
 * @group roster
 */
class RetireWrestlerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_retire_a_bookable_wrestler()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->bookable()->create();

        $response = $this->retireRequest($wrestler);

        $response->assertRedirect(route('wrestlers.index'));
        $this->assertEquals(now()->toDateTimeString(), $wrestler->fresh()->currentRetirement->started_at);
    }

    /** @test */
    public function a_super_administrator_can_retire_a_suspended_wrestler()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->suspended()->create();

        $response = $this->retireRequest($wrestler);

        $response->assertRedirect(route('wrestlers.index'));
        $this->assertEquals(now()->toDateTimeString(), $wrestler->fresh()->currentRetirement->started_at);
    }

    /** @test */
    public function a_super_administrator_can_retire_an_injured_wrestler()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->injured()->create();

        $response = $this->retireRequest($wrestler);

        $response->assertRedirect(route('wrestlers.index'));
        $this->assertEquals(now()->toDateTimeString(), $wrestler->fresh()->currentRetirement->started_at);
    }
}
