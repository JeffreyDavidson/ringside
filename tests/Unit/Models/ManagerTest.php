<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Manager;
use App\Exceptions\CannotBeInjuredException;
use App\Exceptions\CannotBeRetiredException;
use App\Exceptions\CannotBeRecoveredException;
use App\Exceptions\CannotBeSuspendedException;
use App\Exceptions\CannotBeUnretiredException;
use App\Exceptions\CannotBeReinstatedException;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group roster
 */
class ManagerTest extends TestCase
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
        Manager::unsetEventDispatcher();
    }

    /** @test */
    public function a_manager_has_a_first_name()
    {
        $manager = factory(Manager::class)->make(['first_name' => 'John']);

        $this->assertEquals('John', $manager->first_name);
    }

    /** @test */
    public function a_manager_has_a_last_name()
    {
        $manager = factory(Manager::class)->make(['last_name' => 'Smith']);

        $this->assertEquals('Smith', $manager->last_name);
    }

    /** @test */
    public function a_manager_has_a_status()
    {
        $manager = factory(Manager::class)->make(['status' => 'Example Status']);

        $this->assertEquals('Example Status', $manager->status);
    }

    /** @test */
    public function a_manager_can_be_soft_deleted()
    {
        $this->assertSoftDeletes(Manager::class);
    }

    /** @test */
    public function manager_uses_has_full_name_trait()
    {
        $this->assertUsesTrait(\App\Models\Concerns\HasFullName::class, Manager::class);
    }

    /** @test */
    public function a_manager_has_a_full_name()
    {
        $manager = factory(Manager::class)->make(['first_name' => 'John', 'last_name' => 'Smith']);

        $this->assertEquals('John Smith', $manager->full_name);
    }

    /** @test */
    public function manager_uses_has_cached_attributes_trait()
    {
        $this->assertUsesTrait(\App\Traits\HasCachedAttributes::class, Manager::class);
    }

    /** @test */
    public function manager_uses_can_be_employed_trait()
    {
        $this->assertUsesTrait(\App\Models\Concerns\CanBeEmployed::class, Manager::class);
    }

    /** @test */
    public function manager_can_be_employed_default_to_now()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $manager = factory(Manager::class)->create();

        $manager->employ();

        $this->assertCount(1, $manager->employments);
        $this->assertEquals($now->toDateTimeString(), $manager->employment->started_at);
    }

    /** @test */
    public function manager_can_be_employed_at_start_date()
    {
        $yesterday = Carbon::yesterday();
        Carbon::setTestNow($yesterday);

        $manager = factory(Manager::class)->create();

        $manager->employ($yesterday);

        $this->assertEquals($yesterday->toDateTimeString(), $manager->employment->started_at);
    }

    /** @test */
    public function manager_with_an_employment_in_the_future_can_be_employed_at_start_date()
    {
        $today = Carbon::today();
        Carbon::setTestNow($today);

        $manager = factory(Manager::class)->create();
        $manager->employments()->create(['started_at' => Carbon::tomorrow()]);

        $manager->employ($today);

        $this->assertEquals($today->toDateTimeString(), $manager->employment->started_at);
    }

    /** @test */
    public function a_manager_with_an_employment_now_or_in_the_past_is_employed()
    {
        $manager = factory(Manager::class)->create();
        $manager->employment()->create(['started_at' => Carbon::now()]);

        $this->assertTrue($manager->is_employed);
    }

    /** @test */
    public function a_manager_with_an_employment_in_the_future_is_not_employed()
    {
        $manager = factory(Manager::class)->create();
        $manager->employment()->create(['started_at' => Carbon::tomorrow()]);

        $this->assertFalse($manager->is_employed);
    }

    /** @test */
    public function a_manager_without_an_employment_is_not_employed()
    {
        $manager = factory(Manager::class)->create();

        $this->assertFalse($manager->is_employed);
    }

    /** @test */
    public function it_can_get_pending_employment_managers()
    {
        $pendingEmploymentManager = factory(Manager::class)->states('pending-employment')->create();
        $bookableManager = factory(Manager::class)->states('bookable')->create();
        $injuredManager = factory(Manager::class)->states('injured')->create();
        $suspendedManager = factory(Manager::class)->states('suspended')->create();
        $retiredManager = factory(Manager::class)->states('retired')->create();

        $pendingEmploymentManagers = Manager::pendingEmployment()->get();

        $this->assertCount(1, $pendingEmploymentManagers);
        $this->assertTrue($pendingEmploymentManagers->contains($pendingEmploymentManager));
        $this->assertFalse($pendingEmploymentManagers->contains($bookableManager));
        $this->assertFalse($pendingEmploymentManagers->contains($injuredManager));
        $this->assertFalse($pendingEmploymentManagers->contains($suspendedManager));
        $this->assertFalse($pendingEmploymentManagers->contains($retiredManager));
    }

    /** @test */
    public function it_can_get_employed_managers()
    {
        $pendingEmploymentManager = factory(Manager::class)->states('pending-employment')->create();
        $bookableManager = factory(Manager::class)->states('bookable')->create();
        $injuredManager = factory(Manager::class)->states('injured')->create();
        $suspendedManager = factory(Manager::class)->states('suspended')->create();
        $retiredManager = factory(Manager::class)->states('retired')->create();

        $employedManagers = Manager::employed()->get();

        $this->assertCount(4, $employedManagers);
        $this->assertFalse($employedManagers->contains($pendingEmploymentManager));
        $this->assertTrue($employedManagers->contains($bookableManager));
        $this->assertTrue($employedManagers->contains($injuredManager));
        $this->assertTrue($employedManagers->contains($suspendedManager));
        $this->assertTrue($employedManagers->contains($retiredManager));
    }

    /** @test */
    public function manager_uses_can_be_retired_trait()
    {
        $this->assertUsesTrait(\App\Models\Concerns\CanBeRetired::class, Manager::class);
    }

    /** @test */
    public function a_manager_with_a_status_of_retired_is_retired()
    {
        $manager = factory(Manager::class)->create(['status' => 'retired']);

        $this->assertTrue($manager->is_retired);
    }

    /** @test */
    public function a_manager_with_a_retirement_is_retired()
    {
        $manager = factory(Manager::class)->states('retired')->create();

        $this->assertTrue($manager->checkIsRetired());
    }

    /** @test */
    public function it_can_get_retired_managers()
    {
        $retiredManager = factory(Manager::class)->states('retired')->create();
        $pendingEmploymentManager = factory(Manager::class)->states('pending-employment')->create();
        $bookableManager = factory(Manager::class)->states('bookable')->create();
        $injuredManager = factory(Manager::class)->states('injured')->create();
        $suspendedManager = factory(Manager::class)->states('suspended')->create();

        $retiredManagers = Manager::retired()->get();

        $this->assertCount(1, $retiredManagers);
        $this->assertTrue($retiredManagers->contains($retiredManager));
        $this->assertFalse($retiredManagers->contains($pendingEmploymentManager));
        $this->assertFalse($retiredManagers->contains($bookableManager));
        $this->assertFalse($retiredManagers->contains($injuredManager));
        $this->assertFalse($retiredManagers->contains($suspendedManager));
    }

    /** @test */
    public function a_bookable_manager_can_be_retired()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        Manager::setEventDispatcher($this->app['events']);

        $manager = factory(Manager::class)->states('bookable')->create();

        $manager->retire();

        $this->assertEquals('retired', $manager->status);
        $this->assertCount(1, $manager->retirements);
        $this->assertEquals($now->toDateTimeString(), $manager->retirement->started_at);
    }

    /** @test */
    public function a_suspended_manager_can_be_retired()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        Manager::setEventDispatcher($this->app['events']);

        $manager = factory(Manager::class)->states('suspended')->create();

        $this->assertNull($manager->suspensions()->latest()->first()->ended_at);

        $manager->retire();

        $this->assertEquals('retired', $manager->status);
        $this->assertCount(1, $manager->retirements);
        $this->assertNotNull($manager->suspensions()->latest()->first()->ended_at);
        $this->assertEquals($now->toDateTimeString(), $manager->retirement->started_at);
    }

    /** @test */
    public function an_injured_manager_can_be_retired()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        Manager::setEventDispatcher($this->app['events']);

        $manager = factory(Manager::class)->states('injured')->create();

        $this->assertNull($manager->injuries()->latest()->first()->ended_at);

        $manager->retire();

        $this->assertEquals('retired', $manager->status);
        $this->assertCount(1, $manager->retirements);
        $this->assertNotNull($manager->injuries()->latest()->first()->ended_at);
        $this->assertEquals($now->toDateTimeString(), $manager->retirement->started_at);
    }

    /** @test */
    public function a_pending_employment_manager_cannot_be_retired()
    {
        $this->expectException(CannotBeRetiredException::class);

        $manager = factory(Manager::class)->states('pending-employment')->create();

        $manager->retire();
    }

    /** @test */
    public function a_retired_manager_cannot_be_retired()
    {
        $this->expectException(CannotBeRetiredException::class);

        $manager = factory(Manager::class)->states('retired')->create();

        $manager->retire();
    }

    /** @test */
    public function a_retired_manager_can_be_unretired()
    {
        Manager::setEventDispatcher($this->app['events']);

        $manager = factory(Manager::class)->states('retired')->create();

        $manager->unretire();

        $this->assertEquals('bookable', $manager->status);
        $this->assertNotNull($manager->retirements()->latest()->first()->ended_at);
    }

    /** @test */
    public function a_pending_employment_manager_cannot_be_unretired()
    {
        $this->expectException(CannotBeUnretiredException::class);

        $manager = factory(Manager::class)->states('pending-employment')->create();

        $manager->unretire();
    }

    /** @test */
    public function a_suspended_manager_cannot_be_unretired()
    {
        $this->expectException(CannotBeUnretiredException::class);

        $manager = factory(Manager::class)->states('suspended')->create();

        $manager->unretire();
    }

    /** @test */
    public function an_injured_manager_cannot_be_unretired()
    {
        $this->expectException(CannotBeUnretiredException::class);

        $manager = factory(Manager::class)->states('suspended')->create();

        $manager->unretire();
    }

    /** @test */
    public function a_bookable_manager_cannot_be_unretired()
    {
        $this->expectException(CannotBeUnretiredException::class);

        $manager = factory(Manager::class)->states('bookable')->create();

        $manager->unretire();
    }

    /** @test */
    public function manager_uses_can_be_injured_trait()
    {
        $this->assertUsesTrait(\App\Models\Concerns\CanBeInjured::class, Manager::class);
    }

    /** @test */
    public function a_bookable_manager_can_be_injured()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        Manager::setEventDispatcher($this->app['events']);

        $manager = factory(Manager::class)->states('bookable')->create();

        $manager->injure();

        $this->assertEquals('injured', $manager->status);
        $this->assertCount(1, $manager->injuries);
        $this->assertNull($manager->injuries()->latest()->first()->ended_at);
        $this->assertEquals($now->toDateTimeString(), $manager->injury->started_at);
    }

    /** @test */
    public function a_pending_employment_manager_cannot_be_injured()
    {
        $this->expectException(CannotBeInjuredException::class);

        $manager = factory(Manager::class)->states('pending-employment')->create();

        $manager->injure();
    }

    /** @test */
    public function a_suspended_manager_cannot_be_injured()
    {
        $this->expectException(CannotBeInjuredException::class);

        $manager = factory(Manager::class)->states('suspended')->create();

        $manager->injure();
    }

    /** @test */
    public function a_retired_manager_cannot_be_injured()
    {
        $this->expectException(CannotBeInjuredException::class);

        $manager = factory(Manager::class)->states('retired')->create();

        $manager->injure();
    }

    /** @test */
    public function an_injured_manager_cannot_be_injured()
    {
        $this->expectException(CannotBeInjuredException::class);

        $manager = factory(Manager::class)->states('injured')->create();

        $manager->injure();
    }

    /** @test */
    public function a_bookable_manager_cannot_be_recovered()
    {
        $this->expectException(CannotBeRecoveredException::class);

        $manager = factory(Manager::class)->states('bookable')->create();

        $manager->recover();
    }

    /** @test */
    public function a_pending_employment_manager_cannot_be_recovered()
    {
        $this->expectException(CannotBeRecoveredException::class);

        $manager = factory(Manager::class)->states('pending-employment')->create();

        $manager->recover();
    }

    /** @test */
    public function a_suspended_manager_cannot_be_recovered()
    {
        $this->expectException(CannotBeRecoveredException::class);

        $manager = factory(Manager::class)->states('suspended')->create();

        $manager->recover();
    }

    /** @test */
    public function a_retired_manager_cannot_be_recovered()
    {
        $this->expectException(CannotBeRecoveredException::class);

        $manager = factory(Manager::class)->states('retired')->create();

        $manager->recover();
    }

    /** @test */
    public function an_injured_manager_can_be_recovered()
    {
        Manager::setEventDispatcher($this->app['events']);

        $manager = factory(Manager::class)->states('injured')->create();

        $manager->recover();

        $this->assertEquals('bookable', $manager->status);
        $this->assertNotNull($manager->injuries()->latest()->first()->ended_at);
    }

    /** @test */
    public function a_manager_with_a_status_of_injured_is_injured()
    {
        $manager = factory(Manager::class)->create(['status' => 'injured']);

        $this->assertTrue($manager->is_injured);
    }

    /** @test */
    public function a_manager_with_an_injury_is_injured()
    {
        $manager = factory(Manager::class)->states('injured')->create();

        $this->assertTrue($manager->checkIsInjured());
    }

    /** @test */
    public function it_can_get_injured_managers()
    {
        $injuredManager = factory(Manager::class)->states('injured')->create();
        $pendingEmploymentManager = factory(Manager::class)->states('pending-employment')->create();
        $bookableManager = factory(Manager::class)->states('bookable')->create();
        $suspendedManager = factory(Manager::class)->states('suspended')->create();
        $retiredManager = factory(Manager::class)->states('retired')->create();

        $injuredManagers = Manager::injured()->get();

        $this->assertCount(1, $injuredManagers);
        $this->assertTrue($injuredManagers->contains($injuredManager));
        $this->assertFalse($injuredManagers->contains($pendingEmploymentManager));
        $this->assertFalse($injuredManagers->contains($bookableManager));
        $this->assertFalse($injuredManagers->contains($suspendedManager));
        $this->assertFalse($injuredManagers->contains($retiredManager));;
    }

    /** @test */
    public function manager_uses_can_be_suspended_trait()
    {
        $this->assertUsesTrait(\App\Models\Concerns\CanBeSuspended::class, Manager::class);
    }

    /** @test */
    public function a_bookable_manager_can_be_suspended()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);
        Manager::setEventDispatcher($this->app['events']);

        $manager = factory(Manager::class)->states('bookable')->create();

        $manager->suspend();

        $this->assertEquals('suspended', $manager->status);
        $this->assertCount(1, $manager->suspensions);
        $this->assertNull($manager->suspensions()->latest()->first()->ended_at);
        $this->assertEquals($now->toDateTimeString(), $manager->suspension->started_at);
    }

    /** @test */
    public function a_pending_employment_manager_cannot_be_suspended()
    {
        $this->expectException(CannotBeSuspendedException::class);

        $manager = factory(Manager::class)->states('pending-employment')->create();

        $manager->suspend();
    }

    /** @test */
    public function a_suspended_manager_cannot_be_suspended()
    {
        $this->expectException(CannotBeSuspendedException::class);

        $manager = factory(Manager::class)->states('suspended')->create();

        $manager->suspend();
    }

    /** @test */
    public function a_retired_manager_cannot_be_suspended()
    {
        $this->expectException(CannotBeSuspendedException::class);

        $manager = factory(Manager::class)->states('retired')->create();

        $manager->suspend();
    }

    /** @test */
    public function an_suspended_manager_cannot_be_suspended()
    {
        $this->expectException(CannotBeSuspendedException::class);

        $manager = factory(Manager::class)->states('suspended')->create();

        $manager->suspend();
    }

    /** @test */
    public function a_bookable_manager_cannot_be_reinstated()
    {
        $this->expectException(CannotBeReinstatedException::class);

        $manager = factory(Manager::class)->states('bookable')->create();

        $manager->reinstate();
    }

    /** @test */
    public function a_pending_employment_manager_cannot_be_reinstated()
    {
        $this->expectException(CannotBeReinstatedException::class);

        $manager = factory(Manager::class)->states('pending-employment')->create();

        $manager->reinstate();
    }

    /** @test */
    public function an_injured_manager_cannot_be_reinstated()
    {
        $this->expectException(CannotBeReinstatedException::class);

        $manager = factory(Manager::class)->states('injured')->create();

        $manager->reinstate();
    }

    /** @test */
    public function a_retired_manager_cannot_be_reinstated()
    {
        $this->expectException(CannotBeReinstatedException::class);

        $manager = factory(Manager::class)->states('retired')->create();

        $manager->reinstate();
    }

    /** @test */
    public function a_suspended_manager_can_be_reinstated()
    {
        Manager::setEventDispatcher($this->app['events']);

        $manager = factory(Manager::class)->states('suspended')->create();

        $manager->reinstate();

        $this->assertEquals('bookable', $manager->status);
        $this->assertNotNull($manager->suspensions()->latest()->first()->ended_at);
    }

    /** @test */
    public function a_manager_with_a_status_of_suspended_is_suspended()
    {
        $manager = factory(Manager::class)->create(['status' => 'suspended']);

        $this->assertTrue($manager->is_suspended);
    }

    /** @test */
    public function a_manager_with_a_suspension_is_suspended()
    {
        $manager = factory(Manager::class)->states('suspended')->create();

        $this->assertTrue($manager->checkIsSuspended());
    }

    /** @test */
    public function it_can_get_suspended_managers()
    {
        $suspendedManager = factory(Manager::class)->states('suspended')->create();
        $pendingEmploymentManager = factory(Manager::class)->states('pending-employment')->create();
        $bookableManager = factory(Manager::class)->states('bookable')->create();
        $injuredManager = factory(Manager::class)->states('injured')->create();
        $retiredManager = factory(Manager::class)->states('retired')->create();

        $suspendedManagers = Manager::suspended()->get();

        $this->assertCount(1, $suspendedManagers);
        $this->assertTrue($suspendedManagers->contains($suspendedManager));
        $this->assertFalse($suspendedManagers->contains($pendingEmploymentManager));
        $this->assertFalse($suspendedManagers->contains($bookableManager));
        $this->assertFalse($suspendedManagers->contains($injuredManager));
        $this->assertFalse($suspendedManagers->contains($retiredManager));;
    }

    /** @test */
    public function it_can_get_bookable_managers()
    {
        $bookableManager = factory(Manager::class)->states('bookable')->create();
        $pendingEmploymentManager = factory(Manager::class)->states('pending-employment')->create();
        $injuredManager = factory(Manager::class)->states('injured')->create();
        $suspendedManager = factory(Manager::class)->states('suspended')->create();
        $retiredManager = factory(Manager::class)->states('retired')->create();

        $bookableManagers = Manager::bookable()->get();

        $this->assertCount(1, $bookableManagers);
        $this->assertTrue($bookableManagers->contains($bookableManager));
        $this->assertFalse($bookableManagers->contains($pendingEmploymentManager));
        $this->assertFalse($bookableManagers->contains($injuredManager));
        $this->assertFalse($bookableManagers->contains($suspendedManager));
        $this->assertFalse($bookableManagers->contains($retiredManager));;
    }

    /** @test */
    public function a_manager_with_a_status_of_bookable_is_bookable()
    {
        $manager = factory(Manager::class)->create(['status' => 'bookable']);

        $this->assertTrue($manager->is_bookable);
    }

    /** @test */
    public function a_manager_without_a_suspension_or_injury_or_retirement_and_employed_in_the_path_is_bookable()
    {
        $manager = factory(Manager::class)->states('bookable')->create();
        $manager->employments()->create(['started_at' => Carbon::yesterday()]);

        $this->assertTrue($manager->checkIsBookable());
    }

    /** @test */
    public function manager_uses_can_be_stable_member_trait()
    {
        $this->assertUsesTrait(\App\Models\Concerns\CanBeStableMember::class, Manager::class);
    }
}
