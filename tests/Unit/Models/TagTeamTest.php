<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\TagTeam;
use App\Exceptions\CannotBeFiredException;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group tagteams
 * @group roster
 */
class TagTeamTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set up test environment for this class.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        \Event::fake();
    }

    /** @test */
    public function a_tag_team_has_a_name()
    {
        $tagTeam = factory(TagTeam::class)->create(['name' => 'Example Tag Team Name']);

        $this->assertEquals('Example Tag Team Name', $tagTeam->name);
    }

    /** @test */
    public function a_tag_team_can_have_a_signature_move()
    {
        $tagTeam = factory(TagTeam::class)->create(['signature_move' => 'Example Signature Move']);

        $this->assertEquals('Example Signature Move', $tagTeam->signature_move);
    }

    /** @test */
    public function a_tag_team_has_a_status()
    {
        $tagTeam = factory(TagTeam::class)->create(['status' => 'Example Status']);

        $this->assertEquals('Example Status', $tagTeam->status);
    }

    /** @test */
    public function a_tag_team_has_a_default_status_of_pending_employment()
    {
        $tagTeam = factory(TagTeam::class)->create();

        $this->assertEquals('pending-employment', $tagTeam->status);
    }

    /** @test */
    public function tag_team_wrestlers_are_employed_when_team_is_employed()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $tagTeam = factory(TagTeam::class)->with(2, 'wrestlerHistory')->create();

        $tagTeam->employ();

        $this->assertEquals($now->toDateTimeString(), $tagTeam->currentEmployment->started_at);
        $this->assertEquals($now->toDateTimeString(), $tagTeam->wrestlerHistory->each->currentEmployment->started_at);
    }

    /** @test */
    public function tag_team_can_be_employed_at_start_date()
    {
        $yesterday = Carbon::yesterday();
        Carbon::setTestNow($yesterday);

        $tagTeam = factory(TagTeam::class)->create();

        $tagTeam->employ($yesterday);

        $this->assertEquals($yesterday->toDateTimeString(), $tagTeam->currentEmployment->started_at);
    }

    /** @test */
    public function tag_team_with_an_employment_in_the_future_can_be_employed_at_start_date()
    {
        $today = Carbon::today();
        Carbon::setTestNow($today);

        $tagTeam = factory(TagTeam::class)->create();
        $tagTeam->currentEmployment()->create(['started_at' => Carbon::tomorrow()]);

        $tagTeam->employ($today);

        $this->assertEquals($today->toDateTimeString(), $tagTeam->currentEmployment->started_at);
    }

    /** @test */
    public function a_bookable_tag_team_can_be_fired_default_to_now()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $tagTeam = factory(TagTeam::class)->states('bookable')->create();

        $this->assertNull($tagTeam->currentEmployment->ended_at);

        $tagTeam->fire();

        $this->assertCount(1, $tagTeam->previousEmployments);
        $this->assertEquals($now->toDateTimeString(), $tagTeam->previousEmployment->ended_at);
    }

    /** @test */
    public function a_bookable_tag_team_can_be_fired_at_start_date()
    {
        $yesterday = Carbon::yesterday();
        Carbon::setTestNow($yesterday);

        $tagTeam = factory(TagTeam::class)->states('bookable')->create();

        $this->assertNull($tagTeam->currentEmployment->ended_at);

        $tagTeam->fire($yesterday);

        $this->assertCount(1, $tagTeam->previousEmployments);
        $this->assertEquals($yesterday->toDateTimeString(), $tagTeam->previousEmployment->ended_at);
    }

    /** @test */
    public function a_suspended_tag_team_can_be_fired_default_to_now()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $wrestler = factory(TagTeam::class)->states('suspended')->create();

        $this->assertNull($wrestler->currentSuspension->ended_at);

        $wrestler->fire();

        $this->assertCount(1, $wrestler->previousEmployments);
        $this->assertEquals($now->toDateTimeString(), $wrestler->previousEmployment->ended_at);
        $this->assertNotNull($wrestler->previousSuspension->ended_at);
    }

    /** @test */
    public function a_pending_employment_tag_team_cannot_be_fired()
    {
        $this->expectException(CannotBeFiredException::class);

        $wrestler = factory(TagTeam::class)->states('pending-employment')->create();

        $wrestler->fire();
    }

    /** @test */
    public function a_retired_tag_team_cannot_be_fired()
    {
        $this->expectException(CannotBeFiredException::class);

        $wrestler = factory(TagTeam::class)->states('retired')->create();

        $wrestler->fire();
    }

    /** @test */
    public function a_tag_team_with_an_employment_now_or_in_the_past_is_employed()
    {
        $tagTeam = factory(TagTeam::class)->create();
        $tagTeam->currentEmployment()->create(['started_at' => Carbon::now()]);

        $this->assertTrue($tagTeam->is_employed);
    }

    /** @test */
    public function a_tag_team_with_an_employment_in_the_future_is_not_employed()
    {
        $tagTeam = factory(TagTeam::class)->create();
        $tagTeam->currentEmployment()->create(['started_at' => Carbon::tomorrow()]);

        $this->assertFalse($tagTeam->is_employed);
    }

    /** @test */
    public function a_tag_team_without_an_employment_is_not_employed()
    {
        $tagTeam = factory(TagTeam::class)->create();

        $this->assertFalse($tagTeam->is_employed);
    }

    /** @test */
    public function it_can_get_pending_employment_tagTeams()
    {
        $pendingEmploymentTagTeam = factory(TagTeam::class)->states('pending-employment')->create();
        $bookableTagTeam = factory(TagTeam::class)->states('bookable')->create();
        $suspendedTagTeam = factory(TagTeam::class)->states('suspended')->create();
        $retiredTagTeam = factory(TagTeam::class)->states('retired')->create();

        $pendingEmploymentTagTeams = TagTeam::pendingEmployment()->get();

        $this->assertCount(1, $pendingEmploymentTagTeams);
        $this->assertTrue($pendingEmploymentTagTeams->contains($pendingEmploymentTagTeam));
        $this->assertFalse($pendingEmploymentTagTeams->contains($bookableTagTeam));
        $this->assertFalse($pendingEmploymentTagTeams->contains($suspendedTagTeam));
        $this->assertFalse($pendingEmploymentTagTeams->contains($retiredTagTeam));
    }

    /** @test */
    public function it_can_get_employed_tagTeams()
    {
        $pendingEmploymentTagTeam = factory(TagTeam::class)->states('pending-employment')->create();
        $bookableTagTeam = factory(TagTeam::class)->states('bookable')->create();
        $suspendedTagTeam = factory(TagTeam::class)->states('suspended')->create();
        $retiredTagTeam = factory(TagTeam::class)->states('retired')->create();

        $employedTagTeams = TagTeam::employed()->get();

        $this->assertCount(3, $employedTagTeams);
        $this->assertFalse($employedTagTeams->contains($pendingEmploymentTagTeam));
        $this->assertTrue($employedTagTeams->contains($bookableTagTeam));
        $this->assertTrue($employedTagTeams->contains($suspendedTagTeam));
        $this->assertTrue($employedTagTeams->contains($retiredTagTeam));
    }
}
