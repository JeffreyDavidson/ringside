<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\TagTeam;
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
        TagTeam::unsetEventDispatcher();
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
    public function a_tag_team_can_be_soft_deleted()
    {
        $this->assertSoftDeletes(TagTeam::class);
    }

    /** @test */
    public function tag_team_uses_has_cached_attributes_trait()
    {
        $this->assertUsesTrait(\App\Traits\HasCachedAttributes::class, TagTeam::class);
    }

    /** @test */
    public function tag_team_uses_can_be_employed_trait()
    {
        $this->assertUsesTrait(\App\Models\Concerns\CanBeEmployed::class, TagTeam::class);
    }

    /** @test */
    public function tag_team_can_be_employed_default_to_now()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $tagTeam = factory(TagTeam::class)->create();

        $tagTeam->employ();

        $this->assertCount(1, $tagTeam->employments);
        $this->assertEquals($now->toDateTimeString(), $tagTeam->currentEmployment->started_at);
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
        $tagTeam->employments()->create(['started_at' => Carbon::tomorrow()]);

        $tagTeam->employ($today);

        $this->assertEquals($today->toDateTimeString(), $tagTeam->currentEmployment->started_at);
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

        $this->assertCount(4, $employedTagTeams);
        $this->assertFalse($employedTagTeams->contains($pendingEmploymentTagTeam));
        $this->assertTrue($employedTagTeams->contains($bookableTagTeam));
        $this->assertTrue($employedTagTeams->contains($suspendedTagTeam));
        $this->assertTrue($employedTagTeams->contains($retiredTagTeam));
    }
}
