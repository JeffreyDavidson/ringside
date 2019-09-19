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
class SuspendWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_suspend_a_wrestler()
    {
        $this->actAs('basic-user');
        $wrestler = factory(Wrestler::class)->create();

        $response = $this->suspendRequest($wrestler);

        $response->assertForbidden();
    }
}
