<?php

namespace Tests\Integration\Http\Requests\EventMatches;

use App\Http\Requests\EventMatches\StoreRequest;
use App\Models\User;
use Database\Seeders\MatchTypesTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\EventMatchesRequestDataFactory;
use Tests\TestCase;
use Tests\ValidatesRequests;

/**
 * @group events
 * @group roster
 * @group requests
 */
class StoreRequestTest extends TestCase
{
    use RefreshDatabase,
        ValidatesRequests;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(MatchTypesTableSeeder::class);
    }

    /**
     * @test
     */
    public function an_administrator_is_authorized_to_make_this_request()
    {
        $administrator = User::factory()->administrator()->create();

        $this->createRequest(StoreRequest::class)
            ->by($administrator)
            ->assertAuthorized();
    }

    /**
     * @test
     */
    public function a_non_administrator_is_not_authorized_to_make_this_request()
    {
        $user = User::factory()->create();

        $this->createRequest(StoreRequest::class)
            ->by($user)
            ->assertNotAuthorized();
    }

    /**
     * @test
     */
    public function event_matches_must_be_an_array()
    {
        $this->createRequest(StoreRequest::class)
            ->validate(EventMatchesRequestDataFactory::new()->create([
                'matches' => 'not-an-array',
            ]))
            ->assertFailsValidation(['matches' => 'array']);
    }

    /**
     * @test
     */
    public function event_match_must_be_an_array()
    {
        $this->createRequest(StoreRequest::class)
            ->validate(EventMatchesRequestDataFactory::new()->create([
                'matches' => [
                    0 => 'not-an-array',
                ],
            ]))
            ->assertFailsValidation(['matches.0', 'array']);
    }

    /**
     * @test
     */
    public function event_match_type_id_is_required_with_each_match()
    {
        $this->createRequest(StoreRequest::class)
            ->validate(EventMatchesRequestDataFactory::new()->create([
                'matches' => [
                    0 => [
                        'match_type_id' => null,
                    ],
                ],
            ]))
            ->assertFailsValidation(['matches.0.match_type_id' => 'required_with:matches.*']);
    }

    /**
     * @test
     */
    public function each_event_match_type_id_must_be_an_integer()
    {
        $this->createRequest(StoreRequest::class)
            ->validate(EventMatchesRequestDataFactory::new()->create([
                'matches' => [
                    0 => [
                        'match_type_id' => 'not-an-integer',
                    ],
                ],
            ]))
            ->assertFailsValidation(['matches.0.match_type_id' => 'integer']);
    }

    /**
     * @test
     */
    public function each_event_match_type_must_exist()
    {
        $this->createRequest(StoreRequest::class)
            ->validate(EventMatchesRequestDataFactory::new()->create([
                'matches' => [
                    0 => [
                        'match_type_id' => 999999,
                    ],
                ],
            ]))
            ->assertFailsValidation(['matches.0.match_type_id' => 'exists']);
    }

    /**
     * @test
     */
    public function each_event_match_referee_id_is_required_with_each_match()
    {
        $this->createRequest(StoreRequest::class)
            ->validate(EventMatchesRequestDataFactory::new()->create([
                'matches' => [
                    0 => [
                        'referee_id' => null,
                    ],
                ],
            ]))
            ->assertFailsValidation(['matches.0.referee_id' => 'required_with:matches.*']);
    }

    /**
     * @test
     */
    public function each_event_match_referee_id_must_be_an_integer()
    {
        $this->createRequest(StoreRequest::class)
            ->validate(EventMatchesRequestDataFactory::new()->create([
                'matches' => [
                    0 => [
                        'referee_id' => 'not-an-integer',
                    ],
                ],
            ]))
            ->assertFailsValidation(['matches.0.referee_id' => 'integer']);
    }

    /**
     * @test
     */
    public function each_event_match_referee_id_must_exist()
    {
        $this->createRequest(StoreRequest::class)
            ->validate(EventMatchesRequestDataFactory::new()->create([
                'matches' => [
                    0 => [
                        'referee_id' => 999999,
                    ],
                ],
            ]))
            ->assertFailsValidation(['matches.0.referee_id' => 'exists']);
    }

    /**
     * @test
     */
    public function each_event_match_preview_is_optional()
    {
        $this->createRequest(StoreRequest::class)
            ->validate(EventMatchesRequestDataFactory::new()->create([
                'matches' => [
                    0 => [
                        'preview' => null,
                    ],
                ],
            ]))
            ->assertPassesValidation();
    }
}
