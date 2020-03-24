<?php

namespace Tests\Feature\Wrestlers;

use App\Enums\Role;
use App\Exceptions\CannotBeReinstatedException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\WrestlerFactory;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group roster
 */
class ReinstateWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_reinstate_a_suspended_wrestler()
    {
        $this->actAs(Role::BASIC);
        $wrestler = WrestlerFactory::new()->suspended()->create();

        $response = $this->reinstateRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_reinstate_a_suspended_wrestler()
    {
        $wrestler = WrestlerFactory::new()->suspended()->create();

        $response = $this->reinstateRequest($wrestler);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_bookable_wrestler_cannot_be_reinstated()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeReinstatedException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->bookable()->create();

        $response = $this->reinstateRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_employment_wrestler_cannot_be_reinstated()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeReinstatedException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->pendingEmployment()->create();

        $response = $this->reinstateRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function an_injured_wrestler_cannot_be_reinstated()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeReinstatedException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->injured()->create();

        $response = $this->reinstateRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function an_retired_wrestler_cannot_be_reinstated()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeReinstatedException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->retired()->create();

        $response = $this->reinstateRequest($wrestler);

        $response->assertForbidden();
    }
}
