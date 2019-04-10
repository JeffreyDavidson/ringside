<?php

namespace Tests\Feature\Matches;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Wrestler;
use App\Models\MatchType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateSinglesMatchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed('MatchTypesTableSeeder');
    }

    /**
     * Valid parameters for request.
     *
     * @param  array  $overrides
     * @return array
     */
    private function validParams($overrides = [])
    {
        $eventDate = Event::first()->value('date');
        $matchType = MatchType::where('slug', 'singles')->first();
        $wrestlers = factory(Wrestler::class, 2)->create(['hired_at' => $eventDate->subDays(2)]);

        return array_replace([
            'matches' => [
                [
                    'match_type_id' => $matchType->getKey(),
                    'competitors' => [
                        [
                            'wrestlers' => [$wrestlers[0]->getKey()],
                        ],
                        [
                            'wrestlers' => [$wrestlers[1]->getKey()],
                        ],
                    ],
                    'preview' => 'This is an example match preview.',
                ],
            ]
        ], $overrides);
    }

    /** @test */
    public function an_administrator_can_view_the_form_for_creating_matches_for_a_scheduled_event()
    {
        $this->actAs('administrator');
        $event = factory(Event::class)->states('scheduled')->create();

        $response = $this->get(route('events.matches.create', $event));

        $response->assertViewIs('matches.create');
    }

    /** @test */
    public function a_basic_user_cannot_view_the_form_for_creating_matches_for_a_scheduled_event()
    {
        $this->actAs('basic-user');
        $event = factory(Event::class)->states('scheduled')->create();

        $response = $this->get(route('events.matches.create', $event));

        $response->assertStatus(403);
    }

    /** @test */
    public function a_guest_cannot_view_the_form_for_creating_matches_for_a_scheduled_event()
    {
        $event = factory(Event::class)->states('scheduled')->create();

        $response = $this->get(route('events.matches.create', $event));

        $response->assertRedirect('/login');
    }

    /** @test */
    public function an_admimistrator_cannot_view_the_form_for_creating_matches_for_a_past_event()
    {
        $this->actAs('administrator');
        $event = factory(Event::class)->states('past')->create();

        $response = $this->get(route('events.matches.create', $event));

        $response->assertStatus(403);
    }

    /** @test */
    public function an_administrator_can_create_a_matches_for_a_scheduled_event()
    {
        $this->actAs('administrator');
        $event = factory(Event::class)->states('scheduled')->create();

        $response = $this->post(route('events.matches.store', $event), $this->validParams());
        dd($response);
        $response->assertRedirect(route('events.matches.index', $event));
        tap($event->fresh()->matches->first(), function ($match) {
            // Test competitors here
            $this->assertEquals('This is an example match preview.', $match->preview);
        });
    }

    /** @test */
    public function a_basic_user_cannot_create_an_event()
    {
        $this->actAs('basic-user');
        $event = factory(Event::class)->states('scheduled')->create();

        $response = $this->post(route('events.matches.store', $event), $this->validParams());

        $response->assertStatus(403);
    }

    /** @test */
    public function a_guest_cannot_create_an_event()
    {
        $event = factory(Event::class)->states('scheduled')->create();

        $response = $this->post(route('events.matches.store', $event), $this->validParams());

        $response->assertRedirect('/login');
    }

    /** @test */
    public function each_match_type_id_is_required()
    {
        // $this->withoutExceptionHandling();
        $this->actAs('administrator');
        $event = factory(Event::class)->states('scheduled')->create();

        $validParams = $this->validParams();
        $data = data_set($validParams, 'matches.*.match_type_id', null);

        $response = $this->post(route('events.matches.store', $event), $data);
        // dd($response);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('matches.*.match_type_id');
    }

    /** @test */
    public function each_match_type_id_must_be_an_integer()
    {
        $this->actAs('administrator');
        $event = factory(Event::class)->states('scheduled')->create();

        $validParams = $this->validParams();
        $data = data_set($validParams, 'matches.*.match_type_id', 'not-an-integer');

        $response = $this->post(route('events.matches.store', $event), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('matches.*.match_type_id');
    }

    /** @test */
    public function each_match_type_id_must_exist()
    {
        $this->actAs('administrator');
        $event = factory(Event::class)->states('scheduled')->create();

        $validParams = $this->validParams();
        $data = data_set($validParams, 'matches.*.match_type_id', 99);

        $response = $this->post(route('events.matches.store', $event), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('matches.*.match_type_id');
    }

    /** @test */
    public function each_match_competitors_is_required()
    {
        $this->actAs('administrator');
        $event = factory(Event::class)->states('scheduled')->create();

        $validParams = $this->validParams();
        $data = data_set($validParams, 'matches.*.competitors', null);

        $response = $this->post(route('events.matches.store', $event), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('matches.*.competitors');
    }

    /** @test */
    public function each_match_competitors_must_be_an_array()
    {
        $this->actAs('administrator');
        $event = factory(Event::class)->states('scheduled')->create();

        $validParams = $this->validParams();
        $data = data_set($validParams, 'matches.*.competitors', 'not-an-array');
        // dd($data);
        $response = $this->post(route('events.matches.store', $event), $data);
        // dd($response);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('matches.*.competitors');
    }

    /** @test */
    public function each_match_must_have_at_least_two_sides_of_competitors()
    {
        $this->actAs('administrator');
        $event = factory(Event::class)->states('scheduled')->create();

        $validParams = $this->validParams();
        $data = data_set($validParams, 'matches.*.competitors', [1]);
        // dd($data);

        $response = $this->post(route('events.matches.store', $event), $data);
        // dd($response);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('matches.*.competitors');
    }

    /** @test */
    public function each_match_must_have_the_same_size_as_the_match_type_has_sides()
    {
        $this->actAs('administrator');
        $event = factory(Event::class)->states('scheduled')->create();
        $matchType = MatchType::where('slug', 'singles')->first();

        $validParams = $this->validParams();
        $data = data_set($validParams, 'matches.*.competitors', [1]);

        $response = $this->post(route('events.matches.store', $event), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('matches.*');
    }

    /** @test */
    public function each_match_side_of_competitors_is_required()
    {
        $this->actAs('administrator');
        $event = factory(Event::class)->states('scheduled')->create();

        $validParams = $this->validParams();
        $data = data_set($validParams, 'matches.*.competitors.0', null);

        $response = $this->post(route('events.matches.store', $event), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('matches.*.competitors.*');
    }

    /** @test */
    public function each_match_side_of_competitors_must_be_an_array()
    {
        $this->actAs('administrator');
        $event = factory(Event::class)->states('scheduled')->create();

        $validParams = $this->validParams();
        $data = data_set($validParams, 'matches.*.competitors.0', 'not-an-array');

        $response = $this->post(route('events.matches.store', $event), $data);
        // dd($response);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('matches.*.competitors.*');
    }

    /** @test */
    public function each_match_side_of_competitors_must_contain_a_valid_competitor_type()
    {
        $this->actAs('administrator');
        $event = factory(Event::class)->states('scheduled')->create();

        $validParams = $this->validParams();
        $data = data_set($validParams, 'matches.*.competitors.0.foobar', []);

        $response = $this->post(route('events.matches.store', $event), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('matches.*.competitors');
    }

    /** @test */
    public function each_match_side_competitor_type_must_be_an_array()
    {
        $this->actAs('administrator');
        $event = factory(Event::class)->states('scheduled')->create();

        $validParams = $this->validParams();
        $data = data_set($validParams, 'matches.*.competitors.0.wrestlers', 'not-an-array');
        // dd($data);
        $response = $this->post(route('events.matches.store', $event), $data);
        // dd($response);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('matches.*.competitors.*');
    }

    /** @test */
    public function each_match_side_competitor_must_be_an_integer()
    {
        $this->actAs('administrator');
        $event = factory(Event::class)->states('scheduled')->create();

        $validParams = $this->validParams();
        $data = data_set($validParams, 'matches.*.competitors.0.wrestlers.0', 'not-an-integer');

        $response = $this->post(route('events.matches.store', $event), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('matches.*.competitors.*.wrestlers.*');
    }

    /** @test */
    public function each_match_side_competitor_must_exist()
    {
        $this->actAs('administrator');
        $event = factory(Event::class)->states('scheduled')->create();

        $validParams = $this->validParams();
        $data = data_set($validParams, 'matches.*.competitors.0.wrestlers.0', 999);

        $response = $this->post(route('events.matches.store', $event), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('matches.*.competitors.*.wrestlers.*');
    }

    /** @test */
    public function each_match_side_competitor_must_be_hired_before_the_event_date()
    {
        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->create(['hired_at' => Carbon::today()->addMonths(3)]);
        $event = factory(Event::class)->create(['date' => Carbon::today()->addDays(3)]);

        $validParams = $this->validParams();
        $data = data_set($validParams, 'matches.*.competitors.0.wrestlers.0', $wrestler->getKey());

        $response = $this->post(route('events.matches.store', $event), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('matches.*.competitors.*.wrestlers.*');
    }

    /** @test */
    public function each_match_side_competitor_must_be_hired_before_event_of_match()
    {
        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->create(['hired_at' => Carbon::tomorrow()->addDays(3)]);
        $event = factory(Event::class)->create(['date' => Carbon::tomorrow()]);

        $validParams = $this->validParams();
        $data = data_set($validParams, 'matches.*.competitors.0.wrestlers.0', $wrestler->getKey());

        $response = $this->post(route('events.matches.store', $event), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('matches.*.competitors.*.wrestlers.*');
    }

    /** @test */
    public function each_match_preview_is_required()
    {
        $this->actAs('administrator');
        $event = factory(Event::class)->states('scheduled')->create();

        $validParams = $this->validParams();
        $data = data_set($validParams, 'matches.*.preview', null);

        $response = $this->post(route('events.matches.store', $event), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('matches.*.preview');
    }
}
