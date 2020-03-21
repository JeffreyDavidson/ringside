<?php

namespace Tests\Feature\User\Events;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\EventFactory;
use Tests\TestCase;

/**
 * @group events
 * @group users
 */
class ViewEventFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_view_an_event()
    {
        $this->actAs(Role::BASIC);
        $event = EventFactory::new()->create();

        $response = $this->showRequest($event);

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_view_an_event()
    {
        $event = factory(Event::class)->create();

        $response = $this->get(route('events.show', $event));

        $response->assertRedirect(route('login'));
    }
}
