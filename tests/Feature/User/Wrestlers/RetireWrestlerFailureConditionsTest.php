<?php

namespace Tests\Feature\User\Wrestlers;

use App\Models\Wrestler;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group wrestlers
 * @group users
 * @group roster
 */
class RetireWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_retire_a_wrestler()
    {
        $this->actAs('basic-user');
        $wrestler = factory(Wrestler::class)->create();

        $response = $this->retireRequest($wrestler);

        $response->assertForbidden();
    }
}
