<?php

namespace Tests\Feature\Http\Controllers\TagTeams;

use App\Enums\Role;
use App\Enums\TagTeamStatus;
use App\Enums\WrestlerStatus;
use App\Exceptions\CannotBeReleasedException;
use App\Http\Controllers\Wrestlers\ReleaseController;
use App\Http\Requests\Wrestlers\ReleaseRequest;
use App\Models\TagTeam;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group tagteams
 * @group feature-tagteams
 * @group roster
 * @group feature-rosters
 */
class ReleaseControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider administrators
     */
    public function invoke_releases_a_bookable_tag_team_and_tag_team_partners_and_redirects($administrators)
    {
        $tagTeam = TagTeam::factory()->bookable()->create();

        $this->actAs($administrators)
            ->patch(route('tag-teams.release', $tagTeam))
            ->assertRedirect(route('tag-teams.index'));

        tap($tagTeam->fresh(), function ($tagTeam) {
            $this->assertNotNull($tagTeam->employments->last()->ended_at);
            $this->assertEquals(TagTeamStatus::RELEASED, $tagTeam->status);

            foreach ($tagTeam->currentWrestlers as $wrestler) {
                $this->assertEquals(WrestlerStatus::RELEASED, $wrestler->status);
            }
        });
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function invoke_releases_a_suspended_tag_team_and_tag_team_partners_redirects($administrators)
    {
        $tagTeam = TagTeam::factory()->suspended()->create();

        $this->actAs($administrators)
            ->patch(route('tag-teams.release', $tagTeam))
            ->assertRedirect(route('tag-teams.index'));

        tap($tagTeam->fresh(), function ($tagTeam) {
            $this->assertNotNull($tagTeam->suspensions->last()->ended_at);
            $this->assertNotNull($tagTeam->employments->last()->ended_at);
            $this->assertEquals(TagTeamStatus::RELEASED, $tagTeam->status);

            foreach ($tagTeam->currentWrestlers as $wrestler) {
                $this->assertEquals(WrestlerStatus::RELEASED, $wrestler->status);
            }
        });
    }

    /**
     * @test
     */
    public function invoke_validates_using_a_form_request()
    {
        $this->assertActionUsesFormRequest(ReleaseController::class, '__invoke', ReleaseRequest::class);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_suspend_a_wrestler()
    {
        $wrestler = Wrestler::factory()->create();

        $this->actAs(Role::BASIC)
            ->patch(route('wrestlers.release', $wrestler))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_release_a_wrestler()
    {
        $wrestler = Wrestler::factory()->create();

        $this->patch(route('wrestlers.release', $wrestler))
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function invoke_throws_an_exception_for_releasing_an_unemployed_tag_team($administrators)
    {
        $this->expectException(CannotBeReleasedException::class);
        $this->withoutExceptionHandling();

        $tagTeam = TagTeam::factory()->unemployed()->create();

        $this->actAs($administrators)
            ->patch(route('tag-teams.release', $tagTeam));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function invoke_throws_an_exception_for_releasing_a_future_employed_tag_team($administrators)
    {
        $this->expectException(CannotBeReleasedException::class);
        $this->withoutExceptionHandling();

        $tagTeam = TagTeam::factory()->withFutureEmployment()->create();

        $this->actAs($administrators)
            ->patch(route('tag-teams.release', $tagTeam));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function invoke_throws_an_exception_for_releasing_a_released_tag_team($administrators)
    {
        $this->expectException(CannotBeReleasedException::class);
        $this->withoutExceptionHandling();

        $tagTeam = TagTeam::factory()->released()->create();

        $this->actAs($administrators)
            ->patch(route('tag-teams.release', $tagTeam));
    }

    /**
     * @test
     * @dataProvider administrators
     */
    public function invoke_throws_an_exception_for_releasing_a_retired_tag_team($administrators)
    {
        $this->expectException(CannotBeReleasedException::class);
        $this->withoutExceptionHandling();

        $tagTeam = TagTeam::factory()->retired()->create();

        $this->actAs($administrators)
            ->patch(route('tag-teams.release', $tagTeam));
    }
}
