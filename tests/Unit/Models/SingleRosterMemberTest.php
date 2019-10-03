<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Manager;
use App\Models\Referee;
use App\Models\Wrestler;
use App\Exceptions\CannotBeFiredException;
use App\Exceptions\CannotBeInjuredException;
use App\Exceptions\CannotBeRetiredException;
use App\Exceptions\CannotBeUnretiredException;
use App\Exceptions\CannotBeRecoveredException;
use App\Exceptions\CannotBeSuspendedException;
use App\Exceptions\CannotBeReinstatedException;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group roster
 */
class SingleRosterMemberTest extends TestCase
{
    use RefreshDatabase;

    /** 
    * @test
    * @dataProvider modelClassDataProvider 
    */
    public function a_single_roster_member_can_be_employed_default_to_now($modelClass)
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $model = factory($modelClass)->create();

        $model->employ();

        $this->assertCount(1, $model->employments);
        $this->assertEquals($now->toDateTimeString(), $model->currentEmployment->started_at);
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_single_roster_member_can_be_employed_at_start_date($modelClass)
    {
        $yesterday = Carbon::yesterday();
        Carbon::setTestNow($yesterday);

        $model = factory($modelClass)->create();

        $model->employ($yesterday);

        $this->assertEquals($yesterday->toDateTimeString(), $model->currentEmployment->started_at);
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_single_roster_member_with_an_employment_in_the_future_can_be_employed_at_start_date($modelClass)
    {
        $today = Carbon::today();
        Carbon::setTestNow($today);

        $model = factory($modelClass)->create();
        $model->employments()->create(['started_at' => Carbon::tomorrow()]);

        $model->employ($today);

        $this->assertEquals($today->toDateTimeString(), $model->currentEmployment->started_at);
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_bookable_single_roster_member_can_be_fired_default_to_now($modelClass)
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $model = factory($modelClass)->states('bookable')->create();

        $this->assertNull($model->currentEmployment->ended_at);

        $model->fire();

        $this->assertCount(1, $model->previousEmployments);
        $this->assertEquals($now->toDateTimeString(), $model->previousEmployment->ended_at);
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_bookable_single_roster_member_can_be_fired_at_start_date($modelClass)
    {
        $yesterday = Carbon::yesterday();
        Carbon::setTestNow($yesterday);

        $model = factory($modelClass)->states('bookable')->create();

        $this->assertNull($model->currentEmployment->ended_at);

        $model->fire($yesterday);

        $this->assertCount(1, $model->previousEmployments);
        $this->assertEquals($yesterday->toDateTimeString(), $model->previousEmployment->ended_at);
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function an_injured_single_roster_member_can_be_fired_default_to_now($modelClass)
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $model = factory($modelClass)->states('injured')->create();

        $this->assertNull($model->currentInjury->ended_at);

        $model->fire();

        $this->assertCount(1, $model->previousEmployments);
        $this->assertEquals($now->toDateTimeString(), $model->previousEmployment->ended_at);
        $this->assertNotNull($model->previousInjury->ended_at);
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_suspended_single_roster_member_can_be_fired_default_to_now($modelClass)
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $model = factory($modelClass)->states('suspended')->create();

        $this->assertNull($model->currentSuspension->ended_at);

        $model->fire();

        $this->assertCount(1, $model->previousEmployments);
        $this->assertEquals($now->toDateTimeString(), $model->previousEmployment->ended_at);
        $this->assertNotNull($model->previousSuspension->ended_at);
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_pending_employment_single_roster_member_cannot_be_fired($modelClass)
    {
        $this->expectException(CannotBeFiredException::class);

        $model = factory($modelClass)->states('pending-employment')->create();

        $model->fire();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_retired_single_roster_member_cannot_be_fired($modelClass)
    {
        $this->expectException(CannotBeFiredException::class);

        $model = factory($modelClass)->states('retired')->create();

        $model->fire();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_single_roster_member_with_an_employment_now_or_in_the_past_is_employed($modelClass)
    {
        $model = factory($modelClass)->create();
        $model->currentEmployment()->create(['started_at' => Carbon::now()]);

        $this->assertTrue($model->checkIsEmployed());
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function it_can_get_pending_employment_models($modelClass)
    {
        $pendingEmploymentModel = factory($modelClass)->states('pending-employment')->create();
        $bookableModel = factory($modelClass)->states('bookable')->create();
        $injuredModel = factory($modelClass)->states('injured')->create();
        $suspendedModel = factory($modelClass)->states('suspended')->create();
        $retiredModel = factory($modelClass)->states('retired')->create();

        $pendingEmploymentModels = $modelClass::pendingEmployment()->get();

        $this->assertCount(1, $pendingEmploymentModels);
        $this->assertTrue($pendingEmploymentModels->contains($pendingEmploymentModel));
        $this->assertFalse($pendingEmploymentModels->contains($bookableModel));
        $this->assertFalse($pendingEmploymentModels->contains($injuredModel));
        $this->assertFalse($pendingEmploymentModels->contains($suspendedModel));
        $this->assertFalse($pendingEmploymentModels->contains($retiredModel));
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function it_can_get_employed_models($modelClass)
    {
        $pendingEmploymentModel = factory($modelClass)->states('pending-employment')->create();
        $bookableModel = factory($modelClass)->states('bookable')->create();
        $injuredModel = factory($modelClass)->states('injured')->create();
        $suspendedModel = factory($modelClass)->states('suspended')->create();
        $retiredModel = factory($modelClass)->states('retired')->create();

        $employedModels = $modelClass::employed()->get();

        $this->assertCount(4, $employedModels);
        $this->assertFalse($employedModels->contains($pendingEmploymentModel));
        $this->assertTrue($employedModels->contains($bookableModel));
        $this->assertTrue($employedModels->contains($injuredModel));
        $this->assertTrue($employedModels->contains($suspendedModel));
        $this->assertTrue($employedModels->contains($retiredModel));
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_single_roster_member_with_a_retirement_is_retired($modelClass)
    {
        $model = factory($modelClass)->states('retired')->create();

        $this->assertTrue($model->checkIsRetired());
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function it_can_get_retired_models($modelClass)
    {
        $retiredModel = factory($modelClass)->states('retired')->create();
        $pendingEmploymentModel = factory($modelClass)->states('pending-employment')->create();
        $bookableModel = factory($modelClass)->states('bookable')->create();
        $injuredModel = factory($modelClass)->states('injured')->create();
        $suspendedModel = factory($modelClass)->states('suspended')->create();

        $retiredModels = $modelClass::retired()->get();

        $this->assertCount(1, $retiredModels);
        $this->assertTrue($retiredModels->contains($retiredModel));
        $this->assertFalse($retiredModels->contains($pendingEmploymentModel));
        $this->assertFalse($retiredModels->contains($bookableModel));
        $this->assertFalse($retiredModels->contains($injuredModel));
        $this->assertFalse($retiredModels->contains($suspendedModel));
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_bookable_single_roster_member_can_be_retired($modelClass)
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        //$modelClass::setEventDispatcher($this->app['events']);
        // \Event::fake();

        $model = factory($modelClass)->states('bookable')->create();

        $model->retire();

        $this->assertEquals('retired', $model->status);
        $this->assertCount(1, $model->retirements);
        $this->assertEquals($now->toDateTimeString(), $model->currentRetirement->started_at);
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_suspended_single_roster_member_can_be_retired($modelClass)
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        // $modelClass::setEventDispatcher($this->app['events']);

        $model = factory($modelClass)->states('suspended')->create();

        $this->assertNull($model->currentSuspension->ended_at);

        $model->retire();

        $this->assertEquals('retired', $model->status);
        $this->assertCount(1, $model->retirements);
        $this->assertNotNull($model->previousSuspension->ended_at);
        $this->assertEquals($now->toDateTimeString(), $model->currentRetirement->started_at);
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function an_injured_single_roster_member_can_be_retired($modelClass)
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        // $modelClass::setEventDispatcher($this->app['events']);

        $model = factory($modelClass)->states('injured')->create();

        $this->assertNull($model->injuries()->latest()->first()->ended_at);

        $model->retire();

        $this->assertEquals('retired', $model->status);
        $this->assertCount(1, $model->retirements);
        $this->assertNotNull($model->previousInjury->ended_at);
        $this->assertEquals($now->toDateTimeString(), $model->currentRetirement->started_at);
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_pending_employment_single_roster_member_cannot_be_retired($modelClass)
    {
        $this->expectException(CannotBeRetiredException::class);

        $model = factory($modelClass)->states('pending-employment')->create();

        $model->retire();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_retired_single_roster_member_cannot_be_retired($modelClass)
    {
        $this->expectException(CannotBeRetiredException::class);

        $model = factory($modelClass)->states('retired')->create();

        $model->retire();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_retired_single_roster_member_can_be_unretired($modelClass)
    {
        // $modelClass::setEventDispatcher($this->app['events']);

        $model = factory($modelClass)->states('retired')->create();

        $model->unretire();

        $this->assertEquals('bookable', $model->status);
        $this->assertNotNull($model->previousRetirement->ended_at);
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_pending_employment_single_roster_member_cannot_be_unretired($modelClass)
    {
        $this->expectException(CannotBeUnretiredException::class);

        $model = factory($modelClass)->states('pending-employment')->create();

        $model->unretire();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_suspended_single_roster_member_cannot_be_unretired($modelClass)
    {
        $this->expectException(CannotBeUnretiredException::class);

        $model = factory($modelClass)->states('suspended')->create();

        $model->unretire();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function an_injured_single_roster_member_cannot_be_unretired($modelClass)
    {
        $this->expectException(CannotBeUnretiredException::class);

        $model = factory($modelClass)->states('suspended')->create();

        $model->unretire();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_bookable_single_roster_member_cannot_be_unretired($modelClass)
    {
        $this->expectException(CannotBeUnretiredException::class);

        $model = factory($modelClass)->states('bookable')->create();

        $model->unretire();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_single_roster_member_that_retires_and_unretires_has_a_previous_retirement($modelClass)
    {
        $model = factory($modelClass)->states('bookable')->create();
        $model->retire();
        $model->unretire();

        $this->assertCount(1, $model->previousRetirements);
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_bookable_single_roster_member_can_be_injured($modelClass)
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        // $modelClass::setEventDispatcher($this->app['events']);

        $model = factory($modelClass)->states('bookable')->create();

        $model->injure();

        $this->assertEquals('injured', $model->status);
        $this->assertCount(1, $model->injuries);
        $this->assertNull($model->currentInjury->ended_at);
        $this->assertEquals($now->toDateTimeString(), $model->currentInjury->started_at);
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_pending_employment_single_roster_member_cannot_be_injured($modelClass)
    {
        $this->expectException(CannotBeInjuredException::class);

        $model = factory($modelClass)->states('pending-employment')->create();

        $model->injure();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_suspended_single_roster_member_cannot_be_injured($modelClass)
    {
        $this->expectException(CannotBeInjuredException::class);

        $model = factory($modelClass)->states('suspended')->create();

        $model->injure();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_retired_single_roster_member_cannot_be_injured($modelClass)
    {
        $this->expectException(CannotBeInjuredException::class);

        $model = factory($modelClass)->states('retired')->create();

        $model->injure();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function an_injured_single_roster_member_cannot_be_injured($modelClass)
    {
        $this->expectException(CannotBeInjuredException::class);

        $model = factory($modelClass)->states('injured')->create();

        $model->injure();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_bookable_single_roster_member_cannot_be_recovered($modelClass)
    {
        $this->expectException(CannotBeRecoveredException::class);

        $model = factory($modelClass)->states('bookable')->create();

        $model->recover();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_pending_employment_single_roster_member_cannot_be_recovered($modelClass)
    {
        $this->expectException(CannotBeRecoveredException::class);

        $model = factory($modelClass)->states('pending-employment')->create();

        $model->recover();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_suspended_single_roster_member_cannot_be_recovered($modelClass)
    {
        $this->expectException(CannotBeRecoveredException::class);

        $model = factory($modelClass)->states('suspended')->create();

        $model->recover();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_retired_single_roster_member_cannot_be_recovered($modelClass)
    {
        $this->expectException(CannotBeRecoveredException::class);

        $model = factory($modelClass)->states('retired')->create();

        $model->recover();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function an_injured_single_roster_member_can_be_recovered($modelClass)
    {
        // $modelClass::setEventDispatcher($this->app['events']);

        $model = factory($modelClass)->states('injured')->create();

        $model->recover();

        $this->assertEquals('bookable', $model->status);
        $this->assertNotNull($model->previousInjury->ended_at);
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_single_roster_member_with_an_injury_is_injured($modelClass)
    {
        $model = factory($modelClass)->states('injured')->create();

        $this->assertTrue($model->checkIsInjured());
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function it_can_get_injured_models($modelClass)
    {
        $injuredModel = factory($modelClass)->states('injured')->create();
        $pendingEmploymentModel = factory($modelClass)->states('pending-employment')->create();
        $bookableModel = factory($modelClass)->states('bookable')->create();
        $suspendedModel = factory($modelClass)->states('suspended')->create();
        $retiredModel = factory($modelClass)->states('retired')->create();

        $injuredModels = $modelClass::injured()->get();

        $this->assertCount(1, $injuredModels);
        $this->assertTrue($injuredModels->contains($injuredModel));
        $this->assertFalse($injuredModels->contains($pendingEmploymentModel));
        $this->assertFalse($injuredModels->contains($bookableModel));
        $this->assertFalse($injuredModels->contains($suspendedModel));
        $this->assertFalse($injuredModels->contains($retiredModel));;
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_single_roster_member_can_be_injured_multiple_times($modelClass)
    {
        $model = factory($modelClass)->states('injured')->create();

        $model->recover();
        $model->injure();

        $this->assertCount(1, $model->previousInjuries);
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_bookable_single_roster_member_can_be_suspended($modelClass)
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        // $modelClass::setEventDispatcher($this->app['events']);

        $model = factory($modelClass)->states('bookable')->create();

        $model->suspend();

        $this->assertEquals('suspended', $model->status);
        $this->assertCount(1, $model->suspensions);
        $this->assertNull($model->currentSuspension->ended_at);
        $this->assertEquals($now->toDateTimeString(), $model->currentSuspension->started_at);
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_pending_employment_single_roster_member_cannot_be_suspended($modelClass)
    {
        $this->expectException(CannotBeSuspendedException::class);

        $model = factory($modelClass)->states('pending-employment')->create();

        $model->suspend();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_suspended_single_roster_member_cannot_be_suspended($modelClass)
    {
        $this->expectException(CannotBeSuspendedException::class);

        $model = factory($modelClass)->states('suspended')->create();

        $model->suspend();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_retired_single_roster_member_cannot_be_suspended($modelClass)
    {
        $this->expectException(CannotBeSuspendedException::class);

        $model = factory($modelClass)->states('retired')->create();

        $model->suspend();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function an_suspended_single_roster_member_cannot_be_suspended($modelClass)
    {
        $this->expectException(CannotBeSuspendedException::class);

        $model = factory($modelClass)->states('suspended')->create();

        $model->suspend();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_bookable_single_roster_member_cannot_be_reinstated($modelClass)
    {
        $this->expectException(CannotBeReinstatedException::class);

        $model = factory($modelClass)->states('bookable')->create();

        $model->reinstate();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_pending_employment_single_roster_member_cannot_be_reinstated($modelClass)
    {
        $this->expectException(CannotBeReinstatedException::class);

        $model = factory($modelClass)->states('pending-employment')->create();

        $model->reinstate();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function an_injured_single_roster_member_cannot_be_reinstated($modelClass)
    {
        $this->expectException(CannotBeReinstatedException::class);

        $model = factory($modelClass)->states('injured')->create();

        $model->reinstate();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_retired_single_roster_member_cannot_be_reinstated($modelClass)
    {
        $this->expectException(CannotBeReinstatedException::class);

        $model = factory($modelClass)->states('retired')->create();

        $model->reinstate();
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_suspended_single_roster_member_can_be_reinstated($modelClass)
    {
        // $modelClass::setEventDispatcher($this->app['events']);

        $model = factory($modelClass)->states('suspended')->create();

        $model->reinstate();

        $this->assertEquals('bookable', $model->status);
        $this->assertNotNull($model->previousSuspension->ended_at);
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_single_roster_member_with_a_suspension_is_suspended($modelClass)
    {
        $model = factory($modelClass)->states('suspended')->create();

        $this->assertTrue($model->checkIsSuspended());
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function it_can_get_suspended_models($modelClass)
    {
        $suspendedModel = factory($modelClass)->states('suspended')->create();
        $pendingEmploymentModel = factory($modelClass)->states('pending-employment')->create();
        $bookableModel = factory($modelClass)->states('bookable')->create();
        $injuredModel = factory($modelClass)->states('injured')->create();
        $retiredModel = factory($modelClass)->states('retired')->create();

        $suspendedModels = $modelClass::suspended()->get();

        $this->assertCount(1, $suspendedModels);
        $this->assertTrue($suspendedModels->contains($suspendedModel));
        $this->assertFalse($suspendedModels->contains($pendingEmploymentModel));
        $this->assertFalse($suspendedModels->contains($bookableModel));
        $this->assertFalse($suspendedModels->contains($injuredModel));
        $this->assertFalse($suspendedModels->contains($retiredModel));;
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_single_roster_member_can_be_suspended_multiple_times($modelClass)
    {
        $model = factory($modelClass)->states('suspended')->create();

        $model->reinstate();
        $model->suspend();

        $this->assertCount(1, $model->previousSuspensions);
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function it_can_get_bookable_models($modelClass)
    {
        $bookableModel = factory($modelClass)->states('bookable')->create();
        $pendingEmploymentModel = factory($modelClass)->states('pending-employment')->create();
        $injuredModel = factory($modelClass)->states('injured')->create();
        $suspendedModel = factory($modelClass)->states('suspended')->create();
        $retiredModel = factory($modelClass)->states('retired')->create();

        $bookableModels = $modelClass::bookable()->get();

        $this->assertCount(1, $bookableModels);
        $this->assertTrue($bookableModels->contains($bookableModel));
        $this->assertFalse($bookableModels->contains($pendingEmploymentModel));
        $this->assertFalse($bookableModels->contains($injuredModel));
        $this->assertFalse($bookableModels->contains($suspendedModel));
        $this->assertFalse($bookableModels->contains($retiredModel));;
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_single_roster_member_without_a_suspension_or_injury_or_retirement_and_employed_in_the_past_is_bookable($modelClass)
    {
        $model = factory($modelClass)->states('bookable')->create();
        $model->employments()->create(['started_at' => Carbon::yesterday()]);

        $this->assertTrue($model->checkIsBookable());
    }

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_single_roster_member_without_an_employment_is_pending_employment($modelClass)
    {
        $model = factory($modelClass)->create();

        $this->assertTrue($model->checkIsPendingEmployment());
    } 

    /** 
     * @test 
     * @dataProvider modelClassDataProvider
     */
    public function a_single_roster_member_without_a_suspension_or_injury_or_retirement_and_employed_in_the_future_is_pending_employment($modelClass)
    {
        $model = factory($modelClass)->create();
        $model->employ(Carbon::tomorrow());

        $this->assertTrue($model->checkIsPendingEmployment());
    }

    public function modelClassDataProvider()
    {
        return [[Manager::class], [Referee::class], [Wrestler::class]];
    }
}
