<?php

namespace Tests\Feature\User\Events;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group events
 * @group users
 */
class ViewEventsListFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_view_events_page()
    {
        $this->actAs(Role::BASIC);

        $response = $this->indexRequest('events');

        $response->assertForbidden();
    }
}
