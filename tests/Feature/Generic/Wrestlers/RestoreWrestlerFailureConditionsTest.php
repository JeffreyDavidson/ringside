<?php

namespace Tests\Feature\Generic\Wrestlers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\WrestlerFactory;

/**
 * @group wrestlers
 * @group generics
 * @group roster
 */
class RestoreWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_bookable_wrestler_cannot_be_restored()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->bookable()->create();

        $response = $this->restoreRequest($wrestler);

        $response->assertNotFound();
    }

    /** @test */
    public function a_pending_employment_wrestler_cannot_be_restored()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->pendingEmployment()->create();

        $response = $this->restoreRequest($wrestler);

        $response->assertNotFound();
    }

    /** @test */
    public function a_retired_wrestler_cannot_be_restored()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->retired()->create();

        $response = $this->restoreRequest($wrestler);

        $response->assertNotFound();
    }

    /** @test */
    public function a_suspended_wrestler_cannot_be_restored()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->suspended()->create();

        $response = $this->restoreRequest($wrestler);

        $response->assertNotFound();
    }

    /** @test */
    public function an_injured_wrestler_cannot_be_restored()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->injured()->create();

        $response = $this->restoreRequest($wrestler);

        $response->assertNotFound();
    }
}
