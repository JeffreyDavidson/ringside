<?php

namespace Tests\Feature\User\Events;

use App\Enums\Role;
use Tests\TestCase;
use App\Models\Event;
use Tests\Factories\EventFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group events
 * @group users
 */
class RestoreEventFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_restore_a_deleted_event()
    {
        $this->actAs(Role::BASIC);
        $event = EventFactory::new()->softDeleted()->create();

        $response = $this->put(route('events.restore', $event));

        $response->assertForbidden();
    }
}
