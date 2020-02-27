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
class DeleteEventSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_delete_a_scheduled_event()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $event = EventFactory::new()->scheduled()->create();

        $this->deleteRequest($event);

        $this->assertSoftDeleted('events', ['name' => $event->name]);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_past_event()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $event = EventFactory::new()->past()->create();

        $this->deleteRequest($event);

        $this->assertSoftDeleted('events', ['name' => $event->name]);
    }
}
