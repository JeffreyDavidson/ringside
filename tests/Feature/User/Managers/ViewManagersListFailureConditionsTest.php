<?php

namespace Tests\Feature\User\Managers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group managers
 * @group users
 * @group roster
 */
class ViewManagersListFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_view_managers_page()
    {
        $this->actAs(Role::BASIC);

        $response = $this->indexRequest('managers');

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_get_managers()
    {
        $this->actAs(Role::BASIC);

        $response = $this->ajaxJson(route('managers.index'));

        $response->assertForbidden();
    }
}
