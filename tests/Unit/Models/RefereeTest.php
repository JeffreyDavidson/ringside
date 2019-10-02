<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Referee;
use App\Exceptions\CannotBeFiredException;
use App\Exceptions\CannotBeInjuredException;
use App\Exceptions\CannotBeRetiredException;
use App\Exceptions\CannotBeRecoveredException;
use App\Exceptions\CannotBeSuspendedException;
use App\Exceptions\CannotBeUnretiredException;
use App\Exceptions\CannotBeReinstatedException;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group referees
 * @group roster
 */
class RefereeTest extends TestCase
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
        Referee::unsetEventDispatcher();
    }

    /** @test */
    public function a_referee_has_a_first_name()
    {
        $referee = factory(Referee::class)->make(['first_name' => 'John']);

        $this->assertEquals('John', $referee->first_name);
    }

    /** @test */
    public function a_referee_has_a_last_name()
    {
        $referee = factory(Referee::class)->make(['last_name' => 'Smith']);

        $this->assertEquals('Smith', $referee->last_name);
    }

    /** @test */
    public function a_referee_has_a_status()
    {
        $referee = factory(Referee::class)->make(['status' => 'Example Status']);

        $this->assertEquals('Example Status', $referee->status);
    }

    /** @test */
    public function a_referee_has_a_full_name()
    {
        $referee = factory(Referee::class)->make(['first_name' => 'John', 'last_name' => 'Smith']);

        $this->assertEquals('John Smith', $referee->full_name);
    }

    /** @test */
    public function referee_can_be_employed_default_to_now()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $referee = factory(Referee::class)->create();

        $referee->employ();

        $this->assertCount(1, $referee->employments);
        $this->assertEquals($now->toDateTimeString(), $referee->currentEmployment->started_at);
    }

    /** @test */
    public function referee_can_be_employed_at_start_date()
    {
        $yesterday = Carbon::yesterday();
        Carbon::setTestNow($yesterday);

        $referee = factory(Referee::class)->create();

        $referee->employ($yesterday);

        $this->assertEquals($yesterday->toDateTimeString(), $referee->currentEmployment->started_at);
    }

    /** @test */
    public function referee_with_an_employment_in_the_future_can_be_employed_at_start_date()
    {
        $today = Carbon::today();
        Carbon::setTestNow($today);

        $referee = factory(Referee::class)->create();
        $referee->employments()->create(['started_at' => Carbon::tomorrow()]);

        $referee->employ($today);

        $this->assertEquals($today->toDateTimeString(), $referee->currentEmployment->started_at);
    }

    /** @test */
    public function a_bookable_referee_can_be_fired_default_to_now()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $referee = factory(Referee::class)->states('bookable')->create();

        $this->assertNull($referee->currentEmployment->ended_at);

        $referee->fire();

        $this->assertCount(1, $referee->previousEmployments);
        $this->assertEquals($now->toDateTimeString(), $referee->previousEmployment->ended_at);
    }

    /** @test */
    public function a_bookable_referee_can_be_fired_at_start_date()
    {
        $yesterday = Carbon::yesterday();
        Carbon::setTestNow($yesterday);

        $referee = factory(Referee::class)->states('bookable')->create();

        $this->assertNull($referee->currentEmployment->ended_at);

        $referee->fire($yesterday);

        $this->assertCount(1, $referee->previousEmployments);
        $this->assertEquals($yesterday->toDateTimeString(), $referee->previousEmployment->ended_at);
    }

    /** @test */
    public function an_injured_referee_can_be_fired_default_to_now()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $referee = factory(Referee::class)->states('injured')->create();

        $this->assertNull($referee->currentInjury->ended_at);

        $referee->fire();

        $this->assertCount(1, $referee->previousEmployments);
        $this->assertEquals($now->toDateTimeString(), $referee->previousEmployment->ended_at);
        $this->assertNotNull($referee->previousInjury->ended_at);
    }

    /** @test */
    public function a_suspended_referee_can_be_fired_default_to_now()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $referee = factory(Referee::class)->states('suspended')->create();

        $this->assertNull($referee->currentSuspension->ended_at);

        $referee->fire();

        $this->assertCount(1, $referee->previousEmployments);
        $this->assertEquals($now->toDateTimeString(), $referee->previousEmployment->ended_at);
        $this->assertNotNull($referee->previousSuspension->ended_at);
    }

    /** @test */
    public function a_pending_employment_referee_cannot_be_fired()
    {
        $this->expectException(CannotBeFiredException::class);

        $referee = factory(Referee::class)->states('pending-employment')->create();

        $referee->fire();
    }

    /** @test */
    public function a_retired_referee_cannot_be_fired()
    {
        $this->expectException(CannotBeFiredException::class);

        $referee = factory(Referee::class)->states('retired')->create();

        $referee->fire();
    }

    /** @test */
    public function a_referee_with_an_employment_now_or_in_the_past_is_employed()
    {
        $referee = factory(Referee::class)->create();
        $referee->currentEmployment()->create(['started_at' => Carbon::now()]);

        $this->assertTrue($referee->is_employed);
        $this->assertTrue($referee->checkIsEmployed());
    }

    /** @test */
    public function a_referee_with_an_employment_in_the_future_is_not_employed()
    {
        $referee = factory(Referee::class)->create();
        $referee->currentEmployment()->create(['started_at' => Carbon::tomorrow()]);

        $this->assertFalse($referee->is_employed);
        $this->assertTrue($referee->is_pending_employment);
    }

    /** @test */
    public function a_referee_without_an_employment_is_not_employed()
    {
        $referee = factory(Referee::class)->create();

        $this->assertFalse($referee->is_employed);
        $this->assertTrue($referee->is_pending_employment);
    }

    /** @test */
    public function it_can_get_pending_employment_referees()
    {
        $pendingEmploymentReferee = factory(Referee::class)->states('pending-employment')->create();
        $bookableReferee = factory(Referee::class)->states('bookable')->create();
        $injuredReferee = factory(Referee::class)->states('injured')->create();
        $suspendedReferee = factory(Referee::class)->states('suspended')->create();
        $retiredReferee = factory(Referee::class)->states('retired')->create();

        $pendingEmploymentReferees = Referee::pendingEmployment()->get();

        $this->assertCount(1, $pendingEmploymentReferees);
        $this->assertTrue($pendingEmploymentReferees->contains($pendingEmploymentReferee));
        $this->assertFalse($pendingEmploymentReferees->contains($bookableReferee));
        $this->assertFalse($pendingEmploymentReferees->contains($injuredReferee));
        $this->assertFalse($pendingEmploymentReferees->contains($suspendedReferee));
        $this->assertFalse($pendingEmploymentReferees->contains($retiredReferee));
    }

    /** @test */
    public function it_can_get_employed_referees()
    {
        $pendingEmploymentReferee = factory(Referee::class)->states('pending-employment')->create();
        $bookableReferee = factory(Referee::class)->states('bookable')->create();
        $injuredReferee = factory(Referee::class)->states('injured')->create();
        $suspendedReferee = factory(Referee::class)->states('suspended')->create();
        $retiredReferee = factory(Referee::class)->states('retired')->create();

        $employedReferees = Referee::employed()->get();

        $this->assertCount(4, $employedReferees);
        $this->assertFalse($employedReferees->contains($pendingEmploymentReferee));
        $this->assertTrue($employedReferees->contains($bookableReferee));
        $this->assertTrue($employedReferees->contains($injuredReferee));
        $this->assertTrue($employedReferees->contains($suspendedReferee));
        $this->assertTrue($employedReferees->contains($retiredReferee));
    }

    /** @test */
    public function a_referee_with_a_status_of_retired_is_retired()
    {
        $referee = factory(Referee::class)->create(['status' => 'retired']);

        $this->assertTrue($referee->is_retired);
    }

    /** @test */
    public function a_referee_with_a_retirement_is_retired()
    {
        $referee = factory(Referee::class)->states('retired')->create();

        $this->assertTrue($referee->checkIsRetired());
    }

    /** @test */
    public function it_can_get_retired_referees()
    {
        $retiredReferee = factory(Referee::class)->states('retired')->create();
        $pendingEmploymentReferee = factory(Referee::class)->states('pending-employment')->create();
        $bookableReferee = factory(Referee::class)->states('bookable')->create();
        $injuredReferee = factory(Referee::class)->states('injured')->create();
        $suspendedReferee = factory(Referee::class)->states('suspended')->create();

        $retiredReferees = Referee::retired()->get();

        $this->assertCount(1, $retiredReferees);
        $this->assertTrue($retiredReferees->contains($retiredReferee));
        $this->assertFalse($retiredReferees->contains($pendingEmploymentReferee));
        $this->assertFalse($retiredReferees->contains($bookableReferee));
        $this->assertFalse($retiredReferees->contains($injuredReferee));
        $this->assertFalse($retiredReferees->contains($suspendedReferee));
    }

    /** @test */
    public function a_bookable_referee_can_be_retired()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        Referee::setEventDispatcher($this->app['events']);

        $referee = factory(Referee::class)->states('bookable')->create();

        $referee->retire();

        $this->assertEquals('retired', $referee->status);
        $this->assertCount(1, $referee->retirements);
        $this->assertEquals($now->toDateTimeString(), $referee->currentRetirement->started_at);
    }

    /** @test */
    public function a_suspended_referee_can_be_retired()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        Referee::setEventDispatcher($this->app['events']);

        $referee = factory(Referee::class)->states('suspended')->create();

        $this->assertNull($referee->currentSuspension->ended_at);

        $referee->retire();

        $this->assertEquals('retired', $referee->status);
        $this->assertCount(1, $referee->retirements);
        $this->assertNotNull($referee->previousSuspension->ended_at);
        $this->assertEquals($now->toDateTimeString(), $referee->currentRetirement->started_at);
    }

    /** @test */
    public function an_injured_referee_can_be_retired()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        Referee::setEventDispatcher($this->app['events']);

        $referee = factory(Referee::class)->states('injured')->create();

        $this->assertNull($referee->currentInjury->ended_at);

        $referee->retire();

        $this->assertEquals('retired', $referee->status);
        $this->assertCount(1, $referee->retirements);
        $this->assertNotNull($referee->previousInjury->ended_at);
        $this->assertEquals($now->toDateTimeString(), $referee->currentRetirement->started_at);
    }

    /** @test */
    public function a_pending_employment_referee_cannot_be_retired()
    {
        $this->expectException(CannotBeRetiredException::class);

        $referee = factory(Referee::class)->states('pending-employment')->create();

        $referee->retire();
    }

    /** @test */
    public function a_retired_referee_cannot_be_retired()
    {
        $this->expectException(CannotBeRetiredException::class);

        $referee = factory(Referee::class)->states('retired')->create();

        $referee->retire();
    }

    /** @test */
    public function a_retired_referee_can_be_unretired()
    {
        Referee::setEventDispatcher($this->app['events']);

        $referee = factory(Referee::class)->states('retired')->create();

        $referee->unretire();

        $this->assertEquals('bookable', $referee->status);
        $this->assertNotNull($referee->previousRetirement->ended_at);
    }

    /** @test */
    public function a_pending_employment_referee_cannot_be_unretired()
    {
        $this->expectException(CannotBeUnretiredException::class);

        $referee = factory(Referee::class)->states('pending-employment')->create();

        $referee->unretire();
    }

    /** @test */
    public function a_suspended_referee_cannot_be_unretired()
    {
        $this->expectException(CannotBeUnretiredException::class);

        $referee = factory(Referee::class)->states('suspended')->create();

        $referee->unretire();
    }

    /** @test */
    public function an_injured_referee_cannot_be_unretired()
    {
        $this->expectException(CannotBeUnretiredException::class);

        $referee = factory(Referee::class)->states('suspended')->create();

        $referee->unretire();
    }

    /** @test */
    public function a_bookable_referee_cannot_be_unretired()
    {
        $this->expectException(CannotBeUnretiredException::class);

        $referee = factory(Referee::class)->states('bookable')->create();

        $referee->unretire();
    }

    /** @test */
    public function a_referee_that_retires_and_unretires_has_a_previous_retirement()
    {
        $referee = factory(Referee::class)->states('bookable')->create();
        $referee->retire();
        $referee->unretire();

        $this->assertCount(1, $referee->previousRetirements);
    }

    /** @test */
    public function a_bookable_referee_can_be_injured()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        Referee::setEventDispatcher($this->app['events']);

        $referee = factory(Referee::class)->states('bookable')->create();

        $referee->injure();

        $this->assertEquals('injured', $referee->status);
        $this->assertCount(1, $referee->injuries);
        $this->assertNull($referee->currentInjury->ended_at);
        $this->assertEquals($now->toDateTimeString(), $referee->currentInjury->started_at);
    }

    /** @test */
    public function a_pending_employment_referee_cannot_be_injured()
    {
        $this->expectException(CannotBeInjuredException::class);

        $referee = factory(Referee::class)->states('pending-employment')->create();

        $referee->injure();
    }

    /** @test */
    public function a_suspended_referee_cannot_be_injured()
    {
        $this->expectException(CannotBeInjuredException::class);

        $referee = factory(Referee::class)->states('suspended')->create();

        $referee->injure();
    }

    /** @test */
    public function a_retired_referee_cannot_be_injured()
    {
        $this->expectException(CannotBeInjuredException::class);

        $referee = factory(Referee::class)->states('retired')->create();

        $referee->injure();
    }

    /** @test */
    public function an_injured_referee_cannot_be_injured()
    {
        $this->expectException(CannotBeInjuredException::class);

        $referee = factory(Referee::class)->states('injured')->create();

        $referee->injure();
    }

    /** @test */
    public function a_bookable_referee_cannot_be_recovered()
    {
        $this->expectException(CannotBeRecoveredException::class);

        $referee = factory(Referee::class)->states('bookable')->create();

        $referee->recover();
    }

    /** @test */
    public function a_pending_employment_referee_cannot_be_recovered()
    {
        $this->expectException(CannotBeRecoveredException::class);

        $referee = factory(Referee::class)->states('pending-employment')->create();

        $referee->recover();
    }

    /** @test */
    public function a_suspended_referee_cannot_be_recovered()
    {
        $this->expectException(CannotBeRecoveredException::class);

        $referee = factory(Referee::class)->states('suspended')->create();

        $referee->recover();
    }

    /** @test */
    public function a_retired_referee_cannot_be_recovered()
    {
        $this->expectException(CannotBeRecoveredException::class);

        $referee = factory(Referee::class)->states('retired')->create();

        $referee->recover();
    }

    /** @test */
    public function an_injured_referee_can_be_recovered()
    {
        Referee::setEventDispatcher($this->app['events']);

        $referee = factory(Referee::class)->states('injured')->create();

        $referee->recover();

        $this->assertEquals('bookable', $referee->status);
        $this->assertNotNull($referee->previousInjury->ended_at);
    }

    /** @test */
    public function a_referee_with_a_status_of_injured_is_injured()
    {
        $referee = factory(Referee::class)->create(['status' => 'injured']);

        $this->assertTrue($referee->is_injured);
    }

    /** @test */
    public function a_referee_with_an_injury_is_injured()
    {
        $referee = factory(Referee::class)->states('injured')->create();

        $this->assertTrue($referee->checkIsInjured());
    }

    /** @test */
    public function it_can_get_injured_referees()
    {
        $injuredReferee = factory(Referee::class)->states('injured')->create();
        $pendingEmploymentReferee = factory(Referee::class)->states('pending-employment')->create();
        $bookableReferee = factory(Referee::class)->states('bookable')->create();
        $suspendedReferee = factory(Referee::class)->states('suspended')->create();
        $retiredReferee = factory(Referee::class)->states('retired')->create();

        $injuredReferees = Referee::injured()->get();

        $this->assertCount(1, $injuredReferees);
        $this->assertTrue($injuredReferees->contains($injuredReferee));
        $this->assertFalse($injuredReferees->contains($pendingEmploymentReferee));
        $this->assertFalse($injuredReferees->contains($bookableReferee));
        $this->assertFalse($injuredReferees->contains($suspendedReferee));
        $this->assertFalse($injuredReferees->contains($retiredReferee));;
    }

    /** @test */
    public function a_referee_can_be_injured_multiple_times()
    {
        $referee = factory(Referee::class)->states('injured')->create();

        $referee->recover();
        $referee->injure();

        $this->assertCount(1, $referee->previousInjuries);
    }

    /** @test */
    public function a_bookable_referee_can_be_suspended()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        Referee::setEventDispatcher($this->app['events']);

        $referee = factory(Referee::class)->states('bookable')->create();

        $referee->suspend();

        $this->assertEquals('suspended', $referee->status);
        $this->assertCount(1, $referee->suspensions);
        $this->assertNull($referee->currentSuspension->ended_at);
        $this->assertEquals($now->toDateTimeString(), $referee->currentSuspension->started_at);
    }

    /** @test */
    public function a_pending_employment_referee_cannot_be_suspended()
    {
        $this->expectException(CannotBeSuspendedException::class);

        $referee = factory(Referee::class)->states('pending-employment')->create();

        $referee->suspend();
    }

    /** @test */
    public function a_suspended_referee_cannot_be_suspended()
    {
        $this->expectException(CannotBeSuspendedException::class);

        $referee = factory(Referee::class)->states('suspended')->create();

        $referee->suspend();
    }

    /** @test */
    public function a_retired_referee_cannot_be_suspended()
    {
        $this->expectException(CannotBeSuspendedException::class);

        $referee = factory(Referee::class)->states('retired')->create();

        $referee->suspend();
    }

    /** @test */
    public function an_suspended_referee_cannot_be_suspended()
    {
        $this->expectException(CannotBeSuspendedException::class);

        $referee = factory(Referee::class)->states('suspended')->create();

        $referee->suspend();
    }

    /** @test */
    public function a_bookable_referee_cannot_be_reinstated()
    {
        $this->expectException(CannotBeReinstatedException::class);

        $referee = factory(Referee::class)->states('bookable')->create();

        $referee->reinstate();
    }

    /** @test */
    public function a_pending_employment_referee_cannot_be_reinstated()
    {
        $this->expectException(CannotBeReinstatedException::class);

        $referee = factory(Referee::class)->states('pending-employment')->create();

        $referee->reinstate();
    }

    /** @test */
    public function an_injured_referee_cannot_be_reinstated()
    {
        $this->expectException(CannotBeReinstatedException::class);

        $referee = factory(Referee::class)->states('injured')->create();

        $referee->reinstate();
    }

    /** @test */
    public function a_retired_referee_cannot_be_reinstated()
    {
        $this->expectException(CannotBeReinstatedException::class);

        $referee = factory(Referee::class)->states('retired')->create();

        $referee->reinstate();
    }

    /** @test */
    public function a_suspended_referee_can_be_reinstated()
    {
        Referee::setEventDispatcher($this->app['events']);

        $referee = factory(Referee::class)->states('suspended')->create();

        $referee->reinstate();

        $this->assertEquals('bookable', $referee->status);
        $this->assertNotNull($referee->previousSuspension->ended_at);
    }

    /** @test */
    public function a_referee_with_a_status_of_suspended_is_suspended()
    {
        $referee = factory(Referee::class)->create(['status' => 'suspended']);

        $this->assertTrue($referee->is_suspended);
    }

    /** @test */
    public function a_referee_with_a_suspension_is_suspended()
    {
        $referee = factory(Referee::class)->states('suspended')->create();

        $this->assertTrue($referee->checkIsSuspended());
    }

    /** @test */
    public function it_can_get_suspended_referees()
    {
        $suspendedReferee = factory(Referee::class)->states('suspended')->create();
        $pendingEmploymentReferee = factory(Referee::class)->states('pending-employment')->create();
        $bookableReferee = factory(Referee::class)->states('bookable')->create();
        $injuredReferee = factory(Referee::class)->states('injured')->create();
        $retiredReferee = factory(Referee::class)->states('retired')->create();

        $suspendedReferees = Referee::suspended()->get();

        $this->assertCount(1, $suspendedReferees);
        $this->assertTrue($suspendedReferees->contains($suspendedReferee));
        $this->assertFalse($suspendedReferees->contains($pendingEmploymentReferee));
        $this->assertFalse($suspendedReferees->contains($bookableReferee));
        $this->assertFalse($suspendedReferees->contains($injuredReferee));
        $this->assertFalse($suspendedReferees->contains($retiredReferee));;
    }

    /** @test */
    public function a_referee_can_be_suspended_multiple_times()
    {
        $referee = factory(Referee::class)->states('suspended')->create();

        $referee->reinstate();
        $referee->suspend();

        $this->assertCount(1, $referee->previousSuspensions);
    }

    /** @test */
    public function it_can_get_bookable_referees()
    {
        $bookableReferee = factory(Referee::class)->states('bookable')->create();
        $pendingEmploymentReferee = factory(Referee::class)->states('pending-employment')->create();
        $injuredReferee = factory(Referee::class)->states('injured')->create();
        $suspendedReferee = factory(Referee::class)->states('suspended')->create();
        $retiredReferee = factory(Referee::class)->states('retired')->create();

        $bookableReferees = Referee::bookable()->get();

        $this->assertCount(1, $bookableReferees);
        $this->assertTrue($bookableReferees->contains($bookableReferee));
        $this->assertFalse($bookableReferees->contains($pendingEmploymentReferee));
        $this->assertFalse($bookableReferees->contains($injuredReferee));
        $this->assertFalse($bookableReferees->contains($suspendedReferee));
        $this->assertFalse($bookableReferees->contains($retiredReferee));;
    }

    /** @test */
    public function a_referee_with_a_status_of_bookable_is_bookable()
    {
        $referee = factory(Referee::class)->create(['status' => 'bookable']);

        $this->assertTrue($referee->is_bookable);
    }

    /** @test */
    public function a_referee_without_a_suspension_or_injury_or_retirement_and_employed_in_the_past_is_bookable()
    {
        $referee = factory(Referee::class)->states('bookable')->create();
        $referee->employments()->create(['started_at' => Carbon::yesterday()]);

        $this->assertTrue($referee->checkIsBookable());
    }

    /** @test */
    public function a_referee_without_an_employment_is_pending_employment()
    {
        $referee = factory(Referee::class)->create();

        $this->assertTrue($referee->checkIsPendingEmployment());
    }

    /** @test */
    public function a_referee_without_a_suspension_or_injury_or_retirement_and_employed_in_the_future_is_pending_employment()
    {
        $referee = factory(Referee::class)->create();
        $referee->employ(Carbon::tomorrow());

        $this->assertTrue($referee->checkIsPendingEmployment());
    }
}
