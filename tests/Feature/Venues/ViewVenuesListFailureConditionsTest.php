<?php

namespace Tests\Feature\Venues;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group venues
 */
class ViewVenuesListFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_view_venues_page()
    {
        $this->actAs(Role::BASIC);

        $response = $this->indexRequest('venues');

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_view_venues_page()
    {
        $response = $this->indexRequest('venues');

        $response->assertRedirect(route('login'));
    }
}
