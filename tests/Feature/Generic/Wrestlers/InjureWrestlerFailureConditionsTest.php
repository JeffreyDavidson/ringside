<?php

namespace Tests\Feature\Generic\Wrestlers;

use App\Enums\Role;
use App\Exceptions\CannotBeInjuredException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\WrestlerFactory;

/**
 * @group wrestlers
 * @group generics
 * @group roster
 */
class InjureWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_already_injured_wrestler_cannot_be_injured()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeInjuredException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->injured()->create();

        $response = $this->injureRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function a_retired_wrestler_cannot_be_injured()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeInjuredException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->retired()->create();

        $response = $this->injureRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_employment_wrestler_cannot_be_injured()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeInjuredException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->pendingEmployment()->create();

        $response = $this->injureRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function a_suspended_wrestler_cannot_be_injured()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeInjuredException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->suspended()->create();

        $response = $this->injureRequest($wrestler);

        $response->assertForbidden();
    }
}
