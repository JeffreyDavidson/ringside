<?php

namespace Tests\Feature\Generic\Referees;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group generics
 * @group roster
 */
class RestoreRefereeFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_bookable_referee_cannot_be_restored()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->bookable()->create();

        $response = $this->restoreRequest($referee);

        $response->assertNotFound();
    }

    /** @test */
    public function a_suspended_referee_cannot_be_restored()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->suspended()->create();

        $response = $this->restoreRequest($referee);

        $response->assertNotFound();
    }

    /** @test */
    public function a_retired_referee_cannot_be_restored()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->retired()->create();

        $response = $this->restoreRequest($referee);

        $response->assertNotFound();
    }

    /** @test */
    public function a_pending_employment_referee_cannot_be_restored()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->pendingEmployment()->create();

        $response = $this->restoreRequest($referee);

        $response->assertNotFound();
    }

    /** @test */
    public function an_injured_referee_cannot_be_restored()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->injured()->create();

        $response = $this->restoreRequest($referee);

        $response->assertNotFound();
    }
}
