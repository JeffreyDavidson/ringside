<?php

namespace Tests\Feature\Wrestlers;

use App\Enums\Role;
use App\Exceptions\CannotBeRetiredException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\WrestlerFactory;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group roster
 */
class RetireWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_retire_a_wrestler()
    {
        $this->actAs(Role::BASIC);
        $wrestler = WrestlerFactory::new()->create();

        $response = $this->retireRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_retire_a_wrestler()
    {
        $wrestler = WrestlerFactory::new()->create();

        $response = $this->retireRequest($wrestler);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function an_already_retired_wrestler_cannot_be_retired()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeRetiredException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->retired()->create();

        $response = $this->retireRequest($wrestler);

        $response->assertForbidden();
    }
}
