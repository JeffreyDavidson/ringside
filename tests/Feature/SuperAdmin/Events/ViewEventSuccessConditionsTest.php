<?php

namespace Tests\Feature\SuperAdmin\Events;

use App\Enums\Role;
use Tests\TestCase;
use App\Models\Event;
use Tests\Factories\EventFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group events
 * @group superadmins
 */
class ViewEventSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_view_an_event()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $event = EventFactory::new()->create();

        $response = $this->showRequest($event);

        $response->assertViewIs('events.show');
        $this->assertTrue($response->data('event')->is($event));
    }
}
