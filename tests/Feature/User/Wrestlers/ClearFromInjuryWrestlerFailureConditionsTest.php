<?php

namespace Tests\Feature\User\Wrestlers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\WrestlerFactory;

/**
 * @group wrestlers
 * @group users
 * @group roster
 */
class ClearFromInjuryWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_recover_an_injured_wrestler()
    {
        $this->actAs(Role::BASIC);
        $wrestler = WrestlerFactory::new()->injured()->create();

        $response = $this->clearInjuryRequest($wrestler);

        $response->assertForbidden();
    }
}
