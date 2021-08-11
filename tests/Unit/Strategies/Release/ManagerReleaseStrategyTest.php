<?php

namespace Tests\Unit\Strategies\Release;

use App\Models\Manager;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class ManagerReleaseStrategyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_available_single_roster_member_can_be_fired_default_to_now()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $manager = Manager::factory()->available()->create();

        $this->assertNull($manager->currentEmployment->ended_at);

        $manager->release();

        $this->assertCount(1, $manager->previousEmployments);
        $this->assertEquals($now->toDateTimeString(), $manager->previousEmployment->ended_at);
    }

    /**
     * @test
     */
    public function a_available_single_roster_member_can_be_fired_at_start_date()
    {
        $yesterday = Carbon::yesterday();
        Carbon::setTestNow($yesterday);

        $manager = Manager::factory()->available()->create();

        $this->assertNull($manager->currentEmployment->ended_at);

        $manager->release($yesterday);

        $this->assertCount(1, $manager->previousEmployments);
        $this->assertEquals($yesterday->toDateTimeString(), $manager->previousEmployment->ended_at);
    }

    /**
     * @test
     */
    public function an_injured_single_roster_member_can_be_fired_default_to_now()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $manager = Manager::factory()->injured()->create();

        $this->assertNull($manager->currentInjury->ended_at);

        $manager->release();

        $this->assertCount(1, $manager->previousEmployments);
        $this->assertEquals($now->toDateTimeString(), $manager->previousEmployment->ended_at);
        $this->assertNotNull($manager->previousInjury->ended_at);
    }

    /**
     * @test
     */
    public function a_suspended_single_roster_member_can_be_fired_default_to_now()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $manager = Manager::factory()->suspended()->create();

        $this->assertNull($manager->currentSuspension->ended_at);

        $manager->release();

        $this->assertCount(1, $manager->previousEmployments);
        $this->assertEquals($now->toDateTimeString(), $manager->previousEmployment->ended_at);
        $this->assertNotNull($manager->previousSuspension->ended_at);
    }

    /**
     * @test
     */
    public function a_future_employment_single_roster_member_cannot_be_fired()
    {
        $this->expectException(CannotBeReleasedException::class);

        $manager = Manager::factory()->withFutureEmployment()->create();

        $manager->release();
    }

    /**
     * @test
     */
    public function a_retired_single_roster_member_cannot_be_fired()
    {
        $this->expectException(CannotBeReleasedException::class);

        $manager = Manager::factory()->retired()->create();

        $manager->release();
    }
}
