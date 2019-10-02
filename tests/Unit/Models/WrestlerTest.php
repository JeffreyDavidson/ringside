<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Stable;
use App\Models\TagTeam;
use App\Models\Wrestler;
use App\Exceptions\CannotBeFiredException;
use App\Exceptions\CannotBeInjuredException;
use App\Exceptions\CannotBeRetiredException;
use Illuminate\Database\Eloquent\Collection;
use App\Exceptions\CannotBeRecoveredException;
use App\Exceptions\CannotBeSuspendedException;
use App\Exceptions\CannotBeUnretiredException;
use App\Exceptions\CannotBeReinstatedException;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group wrestlers
 * @group roster
 */
class WrestlerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Wrestler::unsetEventDispatcher();
    }

    /** @test */
    public function a_wrestler_has_a_name()
    {
        $wrestler = factory(Wrestler::class)->create(['name' => 'Example Wrestler Name']);

        $this->assertEquals('Example Wrestler Name', $wrestler->name);
    }

    /** @test */
    public function a_wrestler_has_a_height()
    {
        $wrestler = factory(Wrestler::class)->create(['height' => 70]);

        $this->assertEquals('70', $wrestler->height);
    }

    /** @test */
    public function a_wrestler_has_a_weight()
    {
        $wrestler = factory(Wrestler::class)->create(['weight' => 210]);

        $this->assertEquals(210, $wrestler->weight);
    }

    /** @test */
    public function a_wrestler_has_a_hometown()
    {
        $wrestler = factory(Wrestler::class)->create(['hometown' => 'Los Angeles, California']);

        $this->assertEquals('Los Angeles, California', $wrestler->hometown);
    }

    /** @test */
    public function a_wrestler_can_have_a_signature_move()
    {
        $wrestler = factory(Wrestler::class)->create(['signature_move' => 'Example Signature Move']);

        $this->assertEquals('Example Signature Move', $wrestler->signature_move);
    }

    /** @test */
    public function a_wrestler_has_a_status()
    {
        $wrestler = factory(Wrestler::class)->create(['status' => 'Example Status']);

        $this->assertEquals('Example Status', $wrestler->status);
    }

    /** @test */
    public function a_wrestler_has_a_formatted_height()
    {
        $wrestler = factory(Wrestler::class)->create(['height' => 71]);

        $this->assertEquals('5\'11"', $wrestler->formatted_height);
    }

    /** @test */
    public function a_wrestler_can_get_height_in_feet()
    {
        $wrestler = factory(Wrestler::class)->create(['height' => 71]);

        $this->assertEquals(5, $wrestler->feet);
    }

    /** @test */
    public function a_wrestler_can_get_height_in_inches()
    {
        $wrestler = factory(Wrestler::class)->create(['height' => 71]);

        $this->assertEquals(11, $wrestler->inches);
    }

    /** @test */
    public function wrestler_can_be_employed_default_to_now()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $wrestler = factory(Wrestler::class)->create();

        $wrestler->employ();

        $this->assertCount(1, $wrestler->employments);
        $this->assertEquals($now->toDateTimeString(), $wrestler->currentEmployment->started_at);
    }

    /** @test */
    public function wrestler_can_be_employed_at_start_date()
    {
        $yesterday = Carbon::yesterday();
        Carbon::setTestNow($yesterday);

        $wrestler = factory(Wrestler::class)->create();

        $wrestler->employ($yesterday);

        $this->assertEquals($yesterday->toDateTimeString(), $wrestler->currentEmployment->started_at);
    }

    /** @test */
    public function wrestler_with_an_employment_in_the_future_can_be_employed_at_start_date()
    {
        $today = Carbon::today();
        Carbon::setTestNow($today);

        $wrestler = factory(Wrestler::class)->create();
        $wrestler->employments()->create(['started_at' => Carbon::tomorrow()]);

        $wrestler->employ($today);

        $this->assertEquals($today->toDateTimeString(), $wrestler->currentEmployment->started_at);
    }

    /** @test */
    public function a_bookable_wrestler_can_be_fired_default_to_now()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $wrestler = factory(Wrestler::class)->states('bookable')->create();

        $this->assertNull($wrestler->currentEmployment->ended_at);

        $wrestler->fire();

        $this->assertCount(1, $wrestler->previousEmployments);
        $this->assertEquals($now->toDateTimeString(), $wrestler->previousEmployment->ended_at);
    }

    /** @test */
    public function a_bookable_wrestler_can_be_fired_at_start_date()
    {
        $yesterday = Carbon::yesterday();
        Carbon::setTestNow($yesterday);

        $wrestler = factory(Wrestler::class)->states('bookable')->create();

        $this->assertNull($wrestler->currentEmployment->ended_at);

        $wrestler->fire($yesterday);

        $this->assertCount(1, $wrestler->previousEmployments);
        $this->assertEquals($yesterday->toDateTimeString(), $wrestler->previousEmployment->ended_at);
    }

    /** @test */
    public function an_injured_wrestler_can_be_fired_default_to_now()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $wrestler = factory(Wrestler::class)->states('injured')->create();

        $this->assertNull($wrestler->currentInjury->ended_at);

        $wrestler->fire();

        $this->assertCount(1, $wrestler->previousEmployments);
        $this->assertEquals($now->toDateTimeString(), $wrestler->previousEmployment->ended_at);
        $this->assertNotNull($wrestler->previousInjury->ended_at);
    }

    /** @test */
    public function a_suspended_wrestler_can_be_fired_default_to_now()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $this->assertNull($wrestler->currentSuspension->ended_at);

        $wrestler->fire();

        $this->assertCount(1, $wrestler->previousEmployments);
        $this->assertEquals($now->toDateTimeString(), $wrestler->previousEmployment->ended_at);
        $this->assertNotNull($wrestler->previousSuspension->ended_at);
    }

    /** @test */
    public function a_pending_employment_wrestler_cannot_be_fired()
    {
        $this->expectException(CannotBeFiredException::class);

        $wrestler = factory(Wrestler::class)->states('pending-employment')->create();

        $wrestler->fire();
    }

    /** @test */
    public function a_retired_wrestler_cannot_be_fired()
    {
        $this->expectException(CannotBeFiredException::class);

        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $wrestler->fire();
    }

    /** @test */
    public function a_wrestler_with_an_employment_now_or_in_the_past_is_employed()
    {
        $wrestler = factory(Wrestler::class)->create();
        $wrestler->currentEmployment()->create(['started_at' => Carbon::now()]);

        $this->assertTrue($wrestler->is_employed);
        $this->assertTrue($wrestler->checkIsEmployed());
    }

    /** @test */
    public function a_wrestler_with_an_employment_in_the_future_is_not_employed()
    {
        $wrestler = factory(Wrestler::class)->create();
        $wrestler->currentEmployment()->create(['started_at' => Carbon::tomorrow()]);

        $this->assertFalse($wrestler->is_employed);
        $this->assertTrue($wrestler->is_pending_employment);
    }

    /** @test */
    public function a_wrestler_without_an_employment_is_not_employed()
    {
        $wrestler = factory(Wrestler::class)->create();

        $this->assertFalse($wrestler->is_employed);
        $this->assertTrue($wrestler->is_pending_employment);
    }

    /** @test */
    public function it_can_get_pending_employment_wrestlers()
    {
        $pendingEmploymentWrestler = factory(Wrestler::class)->states('pending-employment')->create();
        $bookableWrestler = factory(Wrestler::class)->states('bookable')->create();
        $injuredWrestler = factory(Wrestler::class)->states('injured')->create();
        $suspendedWrestler = factory(Wrestler::class)->states('suspended')->create();
        $retiredWrestler = factory(Wrestler::class)->states('retired')->create();

        $pendingEmploymentWrestlers = Wrestler::pendingEmployment()->get();

        $this->assertCount(1, $pendingEmploymentWrestlers);
        $this->assertTrue($pendingEmploymentWrestlers->contains($pendingEmploymentWrestler));
        $this->assertFalse($pendingEmploymentWrestlers->contains($bookableWrestler));
        $this->assertFalse($pendingEmploymentWrestlers->contains($injuredWrestler));
        $this->assertFalse($pendingEmploymentWrestlers->contains($suspendedWrestler));
        $this->assertFalse($pendingEmploymentWrestlers->contains($retiredWrestler));
    }

    /** @test */
    public function it_can_get_employed_wrestlers()
    {
        $pendingEmploymentWrestler = factory(Wrestler::class)->states('pending-employment')->create();
        $bookableWrestler = factory(Wrestler::class)->states('bookable')->create();
        $injuredWrestler = factory(Wrestler::class)->states('injured')->create();
        $suspendedWrestler = factory(Wrestler::class)->states('suspended')->create();
        $retiredWrestler = factory(Wrestler::class)->states('retired')->create();

        $employedWrestlers = Wrestler::employed()->get();

        $this->assertCount(4, $employedWrestlers);
        $this->assertFalse($employedWrestlers->contains($pendingEmploymentWrestler));
        $this->assertTrue($employedWrestlers->contains($bookableWrestler));
        $this->assertTrue($employedWrestlers->contains($injuredWrestler));
        $this->assertTrue($employedWrestlers->contains($suspendedWrestler));
        $this->assertTrue($employedWrestlers->contains($retiredWrestler));
    }

    /** @test */
    public function a_wrestler_with_a_status_of_retired_is_retired()
    {
        $wrestler = factory(Wrestler::class)->create(['status' => 'retired']);

        $this->assertTrue($wrestler->is_retired);
    }

    /** @test */
    public function a_wrestler_with_a_retirement_is_retired()
    {
        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $this->assertTrue($wrestler->checkIsRetired());
    }

    /** @test */
    public function it_can_get_retired_wrestlers()
    {
        $retiredWrestler = factory(Wrestler::class)->states('retired')->create();
        $pendingEmploymentWrestler = factory(Wrestler::class)->states('pending-employment')->create();
        $bookableWrestler = factory(Wrestler::class)->states('bookable')->create();
        $injuredWrestler = factory(Wrestler::class)->states('injured')->create();
        $suspendedWrestler = factory(Wrestler::class)->states('suspended')->create();

        $retiredWrestlers = Wrestler::retired()->get();

        $this->assertCount(1, $retiredWrestlers);
        $this->assertTrue($retiredWrestlers->contains($retiredWrestler));
        $this->assertFalse($retiredWrestlers->contains($pendingEmploymentWrestler));
        $this->assertFalse($retiredWrestlers->contains($bookableWrestler));
        $this->assertFalse($retiredWrestlers->contains($injuredWrestler));
        $this->assertFalse($retiredWrestlers->contains($suspendedWrestler));
    }

    /** @test */
    public function a_bookable_wrestler_can_be_retired()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        Wrestler::setEventDispatcher($this->app['events']);

        $wrestler = factory(Wrestler::class)->states('bookable')->create();

        $wrestler->retire();

        $this->assertEquals('retired', $wrestler->status);
        $this->assertCount(1, $wrestler->retirements);
        $this->assertEquals($now->toDateTimeString(), $wrestler->currentRetirement->started_at);
    }

    /** @test */
    public function a_suspended_wrestler_can_be_retired()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        Wrestler::setEventDispatcher($this->app['events']);

        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $this->assertNull($wrestler->currentSuspension->ended_at);

        $wrestler->retire();

        $this->assertEquals('retired', $wrestler->status);
        $this->assertCount(1, $wrestler->retirements);
        $this->assertNotNull($wrestler->previousSuspension->ended_at);
        $this->assertEquals($now->toDateTimeString(), $wrestler->currentRetirement->started_at);
    }

    /** @test */
    public function an_injured_wrestler_can_be_retired()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        Wrestler::setEventDispatcher($this->app['events']);

        $wrestler = factory(Wrestler::class)->states('injured')->create();

        $this->assertNull($wrestler->injuries()->latest()->first()->ended_at);

        $wrestler->retire();

        $this->assertEquals('retired', $wrestler->status);
        $this->assertCount(1, $wrestler->retirements);
        $this->assertNotNull($wrestler->previousInjury->ended_at);
        $this->assertEquals($now->toDateTimeString(), $wrestler->currentRetirement->started_at);
    }

    /** @test */
    public function a_pending_employment_wrestler_cannot_be_retired()
    {
        $this->expectException(CannotBeRetiredException::class);

        $wrestler = factory(Wrestler::class)->states('pending-employment')->create();

        $wrestler->retire();
    }

    /** @test */
    public function a_retired_wrestler_cannot_be_retired()
    {
        $this->expectException(CannotBeRetiredException::class);

        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $wrestler->retire();
    }

    /** @test */
    public function a_retired_wrestler_can_be_unretired()
    {
        Wrestler::setEventDispatcher($this->app['events']);

        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $wrestler->unretire();

        $this->assertEquals('bookable', $wrestler->status);
        $this->assertNotNull($wrestler->previousRetirement->ended_at);
    }

    /** @test */
    public function a_pending_employment_wrestler_cannot_be_unretired()
    {
        $this->expectException(CannotBeUnretiredException::class);

        $wrestler = factory(Wrestler::class)->states('pending-employment')->create();

        $wrestler->unretire();
    }

    /** @test */
    public function a_suspended_wrestler_cannot_be_unretired()
    {
        $this->expectException(CannotBeUnretiredException::class);

        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $wrestler->unretire();
    }

    /** @test */
    public function an_injured_wrestler_cannot_be_unretired()
    {
        $this->expectException(CannotBeUnretiredException::class);

        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $wrestler->unretire();
    }

    /** @test */
    public function a_bookable_wrestler_cannot_be_unretired()
    {
        $this->expectException(CannotBeUnretiredException::class);

        $wrestler = factory(Wrestler::class)->states('bookable')->create();

        $wrestler->unretire();
    }

    /** @test */
    public function a_wrestler_that_retires_and_unretires_has_a_previous_retirement()
    {
        $wrestler = factory(Wrestler::class)->states('bookable')->create();
        $wrestler->retire();
        $wrestler->unretire();

        $this->assertCount(1, $wrestler->previousRetirements);
    }

    /** @test */
    public function a_bookable_wrestler_can_be_injured()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        Wrestler::setEventDispatcher($this->app['events']);

        $wrestler = factory(Wrestler::class)->states('bookable')->create();

        $wrestler->injure();

        $this->assertEquals('injured', $wrestler->status);
        $this->assertCount(1, $wrestler->injuries);
        $this->assertNull($wrestler->currentInjury->ended_at);
        $this->assertEquals($now->toDateTimeString(), $wrestler->currentInjury->started_at);
    }

    /** @test */
    public function a_pending_employment_wrestler_cannot_be_injured()
    {
        $this->expectException(CannotBeInjuredException::class);

        $wrestler = factory(Wrestler::class)->states('pending-employment')->create();

        $wrestler->injure();
    }

    /** @test */
    public function a_suspended_wrestler_cannot_be_injured()
    {
        $this->expectException(CannotBeInjuredException::class);

        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $wrestler->injure();
    }

    /** @test */
    public function a_retired_wrestler_cannot_be_injured()
    {
        $this->expectException(CannotBeInjuredException::class);

        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $wrestler->injure();
    }

    /** @test */
    public function an_injured_wrestler_cannot_be_injured()
    {
        $this->expectException(CannotBeInjuredException::class);

        $wrestler = factory(Wrestler::class)->states('injured')->create();

        $wrestler->injure();
    }

    /** @test */
    public function a_bookable_wrestler_cannot_be_recovered()
    {
        $this->expectException(CannotBeRecoveredException::class);

        $wrestler = factory(Wrestler::class)->states('bookable')->create();

        $wrestler->recover();
    }

    /** @test */
    public function a_pending_employment_wrestler_cannot_be_recovered()
    {
        $this->expectException(CannotBeRecoveredException::class);

        $wrestler = factory(Wrestler::class)->states('pending-employment')->create();

        $wrestler->recover();
    }

    /** @test */
    public function a_suspended_wrestler_cannot_be_recovered()
    {
        $this->expectException(CannotBeRecoveredException::class);

        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $wrestler->recover();
    }

    /** @test */
    public function a_retired_wrestler_cannot_be_recovered()
    {
        $this->expectException(CannotBeRecoveredException::class);

        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $wrestler->recover();
    }

    /** @test */
    public function an_injured_wrestler_can_be_recovered()
    {
        Wrestler::setEventDispatcher($this->app['events']);

        $wrestler = factory(Wrestler::class)->states('injured')->create();

        $wrestler->recover();

        $this->assertEquals('bookable', $wrestler->status);
        $this->assertNotNull($wrestler->previousInjury->ended_at);
    }

    /** @test */
    public function a_wrestler_with_a_status_of_injured_is_injured()
    {
        $wrestler = factory(Wrestler::class)->create(['status' => 'injured']);

        $this->assertTrue($wrestler->is_injured);
    }

    /** @test */
    public function a_wrestler_with_an_injury_is_injured()
    {
        $wrestler = factory(Wrestler::class)->states('injured')->create();

        $this->assertTrue($wrestler->checkIsInjured());
    }

    /** @test */
    public function it_can_get_injured_wrestlers()
    {
        $injuredWrestler = factory(Wrestler::class)->states('injured')->create();
        $pendingEmploymentWrestler = factory(Wrestler::class)->states('pending-employment')->create();
        $bookableWrestler = factory(Wrestler::class)->states('bookable')->create();
        $suspendedWrestler = factory(Wrestler::class)->states('suspended')->create();
        $retiredWrestler = factory(Wrestler::class)->states('retired')->create();

        $injuredWrestlers = Wrestler::injured()->get();

        $this->assertCount(1, $injuredWrestlers);
        $this->assertTrue($injuredWrestlers->contains($injuredWrestler));
        $this->assertFalse($injuredWrestlers->contains($pendingEmploymentWrestler));
        $this->assertFalse($injuredWrestlers->contains($bookableWrestler));
        $this->assertFalse($injuredWrestlers->contains($suspendedWrestler));
        $this->assertFalse($injuredWrestlers->contains($retiredWrestler));;
    }

    /** @test */
    public function a_wrestler_can_be_injured_multiple_times()
    {
        $wrestler = factory(Wrestler::class)->states('injured')->create();

        $wrestler->recover();
        $wrestler->injure();

        $this->assertCount(1, $wrestler->previousInjuries);
    }

    /** @test */
    public function a_bookable_wrestler_can_be_suspended()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        Wrestler::setEventDispatcher($this->app['events']);

        $wrestler = factory(Wrestler::class)->states('bookable')->create();

        $wrestler->suspend();

        $this->assertEquals('suspended', $wrestler->status);
        $this->assertCount(1, $wrestler->suspensions);
        $this->assertNull($wrestler->currentSuspension->ended_at);
        $this->assertEquals($now->toDateTimeString(), $wrestler->currentSuspension->started_at);
    }

    /** @test */
    public function a_pending_employment_wrestler_cannot_be_suspended()
    {
        $this->expectException(CannotBeSuspendedException::class);

        $wrestler = factory(Wrestler::class)->states('pending-employment')->create();

        $wrestler->suspend();
    }

    /** @test */
    public function a_suspended_wrestler_cannot_be_suspended()
    {
        $this->expectException(CannotBeSuspendedException::class);

        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $wrestler->suspend();
    }

    /** @test */
    public function a_retired_wrestler_cannot_be_suspended()
    {
        $this->expectException(CannotBeSuspendedException::class);

        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $wrestler->suspend();
    }

    /** @test */
    public function an_suspended_wrestler_cannot_be_suspended()
    {
        $this->expectException(CannotBeSuspendedException::class);

        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $wrestler->suspend();
    }

    /** @test */
    public function a_bookable_wrestler_cannot_be_reinstated()
    {
        $this->expectException(CannotBeReinstatedException::class);

        $wrestler = factory(Wrestler::class)->states('bookable')->create();

        $wrestler->reinstate();
    }

    /** @test */
    public function a_pending_employment_wrestler_cannot_be_reinstated()
    {
        $this->expectException(CannotBeReinstatedException::class);

        $wrestler = factory(Wrestler::class)->states('pending-employment')->create();

        $wrestler->reinstate();
    }

    /** @test */
    public function an_injured_wrestler_cannot_be_reinstated()
    {
        $this->expectException(CannotBeReinstatedException::class);

        $wrestler = factory(Wrestler::class)->states('injured')->create();

        $wrestler->reinstate();
    }

    /** @test */
    public function a_retired_wrestler_cannot_be_reinstated()
    {
        $this->expectException(CannotBeReinstatedException::class);

        $wrestler = factory(Wrestler::class)->states('retired')->create();

        $wrestler->reinstate();
    }

    /** @test */
    public function a_suspended_wrestler_can_be_reinstated()
    {
        Wrestler::setEventDispatcher($this->app['events']);

        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $wrestler->reinstate();

        $this->assertEquals('bookable', $wrestler->status);
        $this->assertNotNull($wrestler->previousSuspension->ended_at);
    }

    /** @test */
    public function a_wrestler_with_a_status_of_suspended_is_suspended()
    {
        $wrestler = factory(Wrestler::class)->create(['status' => 'suspended']);

        $this->assertTrue($wrestler->is_suspended);
    }

    /** @test */
    public function a_wrestler_with_a_suspension_is_suspended()
    {
        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $this->assertTrue($wrestler->checkIsSuspended());
    }

    /** @test */
    public function it_can_get_suspended_wrestlers()
    {
        $suspendedWrestler = factory(Wrestler::class)->states('suspended')->create();
        $pendingEmploymentWrestler = factory(Wrestler::class)->states('pending-employment')->create();
        $bookableWrestler = factory(Wrestler::class)->states('bookable')->create();
        $injuredWrestler = factory(Wrestler::class)->states('injured')->create();
        $retiredWrestler = factory(Wrestler::class)->states('retired')->create();

        $suspendedWrestlers = Wrestler::suspended()->get();

        $this->assertCount(1, $suspendedWrestlers);
        $this->assertTrue($suspendedWrestlers->contains($suspendedWrestler));
        $this->assertFalse($suspendedWrestlers->contains($pendingEmploymentWrestler));
        $this->assertFalse($suspendedWrestlers->contains($bookableWrestler));
        $this->assertFalse($suspendedWrestlers->contains($injuredWrestler));
        $this->assertFalse($suspendedWrestlers->contains($retiredWrestler));;
    }

    /** @test */
    public function a_wrestler_can_be_suspended_multiple_times()
    {
        $wrestler = factory(Wrestler::class)->states('suspended')->create();

        $wrestler->reinstate();
        $wrestler->suspend();

        $this->assertCount(1, $wrestler->previousSuspensions);
    }

    /** @test */
    public function it_can_get_bookable_wrestlers()
    {
        $bookableWrestler = factory(Wrestler::class)->states('bookable')->create();
        $pendingEmploymentWrestler = factory(Wrestler::class)->states('pending-employment')->create();
        $injuredWrestler = factory(Wrestler::class)->states('injured')->create();
        $suspendedWrestler = factory(Wrestler::class)->states('suspended')->create();
        $retiredWrestler = factory(Wrestler::class)->states('retired')->create();

        $bookableWrestlers = Wrestler::bookable()->get();

        $this->assertCount(1, $bookableWrestlers);
        $this->assertTrue($bookableWrestlers->contains($bookableWrestler));
        $this->assertFalse($bookableWrestlers->contains($pendingEmploymentWrestler));
        $this->assertFalse($bookableWrestlers->contains($injuredWrestler));
        $this->assertFalse($bookableWrestlers->contains($suspendedWrestler));
        $this->assertFalse($bookableWrestlers->contains($retiredWrestler));;
    }

    /** @test */
    public function a_wrestler_with_a_status_of_bookable_is_bookable()
    {
        $wrestler = factory(Wrestler::class)->create(['status' => 'bookable']);

        $this->assertTrue($wrestler->is_bookable);
    }

    /** @test */
    public function a_wrestler_without_a_suspension_or_injury_or_retirement_and_employed_in_the_past_is_bookable()
    {
        $wrestler = factory(Wrestler::class)->states('bookable')->create();
        $wrestler->employments()->create(['started_at' => Carbon::yesterday()]);

        $this->assertTrue($wrestler->checkIsBookable());
    }

    /** @test */
    public function a_wrestler_without_an_employment_is_pending_employment()
    {
        $wrestler = factory(Wrestler::class)->create();

        $this->assertTrue($wrestler->checkIsPendingEmployment());
    }

    /** @test */
    public function a_wrestler_without_a_suspension_or_injury_or_retirement_and_employed_in_the_future_is_pending_employment()
    {
        $wrestler = factory(Wrestler::class)->create();
        $wrestler->employ(Carbon::tomorrow());

        $this->assertTrue($wrestler->checkIsPendingEmployment());
    }

    /** @test */
    public function a_wrestler_has_a_current_stable_after_joining()
    {
        $wrestler = factory(Wrestler::class)->states('bookable')->create();
        $stable = factory(Stable::class)->states('active')->create();

        $wrestler->stableHistory()->attach($stable);

        $this->assertEquals($stable->id, $wrestler->currentStable->id);
        $this->assertTrue($wrestler->stableHistory->contains($stable));
    }

    /** @test */
    public function a_stable_remains_in_a_wrestlers_history_after_leaving()
    {
        $wrestler = factory(Wrestler::class)->create();
        $stable = factory(Stable::class)->create();
        $wrestler->stableHistory()->attach($stable);
        $wrestler->stableHistory()->detach($stable);

        $this->assertTrue($wrestler->previousStables->contains($stable));
    }

    /** @test */
    public function a_wrestler_has_a_current_tag_team_after_joining()
    {
        $wrestler = factory(Wrestler::class)->states('bookable')->create();
        $tagTeam = factory(TagTeam::class)->states('bookable')->create();

        $wrestler->tagTeamHistory()->attach($tagTeam);

        $this->assertEquals($tagTeam->id, $wrestler->currentTagTeam->id);
        $this->assertTrue($wrestler->tagTeamHistory->contains($tagTeam));
    }

    /** @test */
    public function a_tag_team_remains_in_a_wrestlers_history_after_leaving()
    {
        $wrestler = factory(Wrestler::class)->create();
        $tagTeam = factory(TagTeam::class)->create();

        $wrestler->tagTeamHistory()->attach($tagTeam);
        $wrestler->tagTeamHistory()->detach($tagTeam);

        $this->assertTrue($wrestler->previousTagTeams->contains($tagTeam));
    }
}
