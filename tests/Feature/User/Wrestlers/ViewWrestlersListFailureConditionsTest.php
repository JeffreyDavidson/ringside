<?php

namespace Tests\Feature\User\Wrestlers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group users
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
}
