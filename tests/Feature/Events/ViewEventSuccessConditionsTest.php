<?php

namespace Tests\Feature\Events;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\EventFactory;
use Tests\Factories\VenueFactory;
use Tests\TestCase;

/**
 * @group events
 */
class ViewEventSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider adminRoles
     */
    public function administrators_can_view_an_event_page($adminRoles)
    {
        $this->actAs($adminRoles);
        $event = EventFactory::new()->create();

        $response = $this->showRequest($event);

        $response->assertViewIs('events.show');
        $this->assertTrue($response->data('event')->is($event));
    }

    /**
     * @test
     * @dataProvider adminRoles
     */
    public function a_events_data_can_be_seen_on_the_event_page($adminRoles)
    {
        $this->actAs($adminRoles);
        $venue = VenueFactory::new()->create(['name' => 'The Awesome Arena']);
        $event = EventFactory::new()->create([
            'name' => 'Event 1',
            'date' => '2020-03-05',
            'venue_id' => $venue->id,
            'preview' => 'This is an example event preview.',
        ]);

        $response = $this->showRequest($event);

        $response->assertSee('Event 1');
        $response->assertSee('March 5, 2020');
        $response->assertSee('The Awesome Arena');
        $response->assertSee('This is an example event preview.');
    }

    public function adminRoles()
    {
        return [
            [Role::ADMINISTRATOR],
            [Role::SUPER_ADMINISTRATOR],
        ];
    }
}
