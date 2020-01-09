<?php

namespace Tests\Feature\Guest\Managers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group guests
 * @group roster
 */
class ViewManagersListFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_view_managers_page()
    {
        $response = $this->indexRequest('manager');

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_get_managers()
    {
        $response = $this->ajaxJson(route('api.managers.index'));

        $response->assertForbidden();
    }
}
