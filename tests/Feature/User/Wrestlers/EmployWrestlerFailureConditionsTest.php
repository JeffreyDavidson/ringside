<?php

namespace Tests\Feature\User\Wrestlers;

use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group wrestlers
 * @group users
 */
class EmployWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_employ_a_pending_employment_wrestler()
    {
        $this->actAs('basic-user');
        $wrestler = factory(Wrestler::class)->states('pending-employment')->create();

        $response = $this->put(route('wrestlers.employ', $wrestler));

        $response->assertForbidden();
    }
}
