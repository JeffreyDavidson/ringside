<?php

namespace Tests\Feature\Generic\Referees;

use App\Enums\Role;
use App\Exceptions\CannotBeEmployedException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group generics
 * @group roster
 */
class EmployRefereeFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_bookable_referee_cannot_be_employed()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeEmployedException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->bookable()->create();

        $response = $this->employRequest($referee);

        $response->assertForbidden();
    }

    /** @test */
    public function a_retired_referee_cannot_be_employed()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeEmployedException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->retired()->create();

        $response = $this->employRequest($referee);

        $response->assertForbidden();
    }

    /** @test */
    public function a_suspended_referee_cannot_be_employed()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeEmployedException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->suspended()->create();

        $response = $this->employRequest($referee);

        $response->assertForbidden();
    }

    /** @test */
    public function an_injured_referee_cannot_be_employed()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeEmployedException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->injured()->create();

        $response = $this->employRequest($referee);

        $response->assertForbidden();
    }
}
