<?php

namespace Tests\Feature\Wrestlers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group roster
 */
class ViewWrestlersListFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_view_wrestlers_page()
    {
        $this->actAs(Role::BASIC);

        $response = $this->indexRequest('wrestlers');

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_view_wrestlers_page()
    {
        $response = $this->indexRequest('wrestler');

        $response->assertRedirect(route('login'));
    }
}
