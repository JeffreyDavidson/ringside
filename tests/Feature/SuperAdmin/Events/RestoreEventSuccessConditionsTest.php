<?php

namespace Tests\Feature\SuperAdmin\Events;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\EventFactory;
use Tests\TestCase;

/**
 * @group events
 * @group superadmins
 */
class RestoreEventSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_restore_a_deleted_event()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $event = EventFactory::new()->softDeleted()->create();

        $response = $this->restoreRequest($event);

        $response->assertRedirect(route('events.index'));
        $this->assertNull($event->fresh()->deleted_at);
    }
}
