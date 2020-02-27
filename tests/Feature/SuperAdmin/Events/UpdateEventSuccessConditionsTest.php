<?php

namespace Tests\Feature\SuperAdmin\Events;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\EventFactory;
use Tests\Factories\VenueFactory;
use Tests\TestCase;

/**
 * @group events
 * @group superadmins
 */
class UpdateEventSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Valid parameters for request.
     *
     * @param  array  $overrides
     * @return array
     */
    private function validParams($overrides = [])
    {
        return array_replace([
            'name' => 'Example Event Name',
            'date' => now()->toDateTimeString(),
            'venue_id' => VenueFactory::new()->create()->id,
            'preview' => 'This is an event preview.',
        ], $overrides);
    }

    /** @test */
    public function a_super_administrator_can_view_the_form_for_editing_a_scheduled_event()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $event = EventFactory::new()->scheduled()->create();

        $response = $this->editRequest($event);

        $response->assertViewIs('events.edit');
        $this->assertTrue($response->data('event')->is($event));
    }

    /** @test */
    public function a_super_administrator_can_update_a_scheduled_event()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $event = EventFactory::new()->scheduled()->create();

        $response = $this->updateRequest($event, $this->validParams());

        $response->assertRedirect(route('events.index'));
        tap($event->fresh(), function ($event) {
            $this->assertEquals('Example Event Name', $event->name);
            $this->assertEquals(now()->toDateTimeString(), $event->date);
            $this->assertEquals('This is an event preview.', $event->preview);
        });
    }
}
