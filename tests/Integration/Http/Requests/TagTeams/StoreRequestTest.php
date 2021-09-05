<?php

namespace Tests\Integration\Http\Requests\TagTeams;

use App\Http\Requests\TagTeams\StoreRequest;
use App\Models\Employment;
use App\Models\TagTeam;
use App\Models\User;
use App\Models\Wrestler;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\Rule;
use Tests\TestCase;
use Tests\ValidatesRequests;

/**
 * @group tagteams
 * @group roster
 * @group requests
 */
class StoreRequestTest extends TestCase
{
    use RefreshDatabase,
        ValidatesRequests;

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
    public function tag_team_name_is_required()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'name' => null,
            ])
            ->assertFailsValidation(['name' => 'required']);
    }

    /**
     * @test
     */
    public function tag_team_name_must_be_a_string()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'name' => 123,
            ])
            ->assertFailsValidation(['name' => 'string']);
    }

    /**
     * @test
     */
    public function tag_team_name_must_be_at_least_3_characters()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'name' => 'ab',
            ])
            ->assertFailsValidation(['name' => 'min:3']);
    }

    /**
     * @test
     */
    public function tag_team_name_must_be_unique()
    {
        TagTeam::factory()->create(['name' => 'Example TagTeam Name']);

        $this->createRequest(StoreRequest::class)
            ->validate([
                'name' => 'Example TagTeam Name',
            ])
            ->assertFailsValidation(['name' => Rule::unique('tag_teams', 'name')]);
    }

    /**
     * @test
     */
    public function tag_team_signature_move_is_optional()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'signature_move' => null,
                'name' => 'Example Tag Team Name',
            ])
            ->assertPassesValidation();
    }

    /**
     * @test
     */
    public function tag_team_signature_move_must_be_a_string_if_provided()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'signature_move' => 12345,
            ])
            ->assertFailsValidation(['signature_move' => 'string']);
    }

    /**
     * @test
     */
    public function tag_team_started_at_is_optional()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'started_at' => null,
                'name' => 'Example Tag Team Name',
            ])
            ->assertPassesValidation();
    }

    /**
     * @test
     */
    public function tag_team_started_at_must_be_a_string_if_provided()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'started_at' => 12345,
            ])
            ->assertFailsValidation(['started_at' => 'string']);
    }

    /**
     * @test
     */
    public function tag_team_started_at_must_be_in_the_correct_date_format()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'started_at' => 'not-a-date',
            ])
            ->assertFailsValidation(['started_at' => 'date']);
    }

    /**
     * @test
     */
    public function tag_team_wrestlers_are_optional()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'wrestlers' => null,
                'name' => 'Example Tag Team Name',
            ])
            ->assertPassesValidation();
    }

    /**
     * @test
     */
    public function tag_team_wrestlers_must_be_an_array_if_provided()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'wrestlers' => 'not-an-array',
            ])
            ->assertFailsValidation(['wrestlers' => 'array']);
    }

    /**
     * @test
     */
    public function tag_team_wrestlers_is_required_with_a_tag_team_signature_move()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'wrestlers' => null,
                'signature_move' => 'Example Signature Move',
            ])
            ->assertFailsValidation(['wrestlers' => 'requiredwith:signature_move']);
    }

    /**
     * @test
     */
    public function each_tag_team_wrestler_must_be_an_integer()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'wrestlers' => ['not-an-integer'],
            ])
            ->assertFailsValidation(['wrestlers.0' => 'integer']);
    }

    /**
     * @test
     */
    public function each_tag_team_wrestler_must_be_distinct()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'wrestlers' => [1, 1],
            ])
            ->assertFailsValidation(['wrestlers.0' => 'distinct']);
    }

    /**
     * @test
     */
    public function each_tag_team_wrestler_must_exist()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'wrestlers' => [1, 2],
            ])
            ->assertFailsValidation(['wrestlers.0' => 'exist']);
    }

    /**
     * @test
     */
    public function each_tag_team_wrestler_must_be_employed_before_tag_team_start_date()
    {
        $wrestlerA = Wrestler::factory()
            ->has(Employment::factory()->started(Carbon::tomorrow()->toDateString()))
            ->create();

        $wrestlerB = Wrestler::factory()
            ->has(Employment::factory()->started(Carbon::tomorrow()->toDateString()))
            ->create();

        $this->createRequest(StoreRequest::class)
            ->validate([
                'started_at' => Carbon::now()->toDateString(),
                'wrestlers' => [$wrestlerA->id, $wrestlerB->id],
            ])
            ->assertFailsValidation(['wrestlers.0' => 'app\rules\cannotbeemployedafterdate']);
    }
}
