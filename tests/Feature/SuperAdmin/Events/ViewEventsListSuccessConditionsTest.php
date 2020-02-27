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
class ViewEventsListSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @var \Illuminate\Support\Collection */
    protected $events;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $scheduled = EventFactory::new()->count(3)->scheduled()->create();
        $past = EventFactory::new()->count(3)->past()->create();

        $this->events = collect([
            'scheduled' => $scheduled,
            'past'      => $past,
            'all'       => collect()
                        ->concat($scheduled)
                        ->concat($past),
        ]);
    }

    /** @test */
    public function a_super_administrator_can_view_events_page()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $response = $this->indexRequest('events');

        $response->assertOk();
        $response->assertViewIs('events.index');
    }

    /** @test */
    public function a_super_administrator_can_view_all_events()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $responseAjax = $this->ajaxJson(route('events.index'));

        $responseAjax->assertJson([
            'recordsTotal' => $this->events->get('all')->count(),
            'data'         => $this->events->get('all')->only(['id'])->toArray(),
        ]);
    }

    /** @test */
    public function a_super_administrator_can_view_scheduled_events()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $responseAjax = $this->ajaxJson(route('events.index', ['status' => 'scheduled']));

        $responseAjax->assertJson([
            'recordsTotal' => $this->events->get('scheduled')->count(),
            'data'         => $this->events->get('scheduled')->only(['id'])->toArray(),
        ]);
    }

    /** @test */
    public function a_super_administrator_can_view_past_events()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $responseAjax = $this->ajaxJson(route('events.index', ['status' => 'past']));

        $responseAjax->assertJson([
            'recordsTotal' => $this->events->get('past')->count(),
            'data'         => $this->events->get('past')->only(['id'])->toArray(),
        ]);
    }
}
