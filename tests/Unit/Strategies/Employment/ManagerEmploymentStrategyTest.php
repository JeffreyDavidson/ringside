<?php

namespace Tests\Unit\Strategies\Employment;

use App\Exceptions\CannotBeEmployedException;
use App\Models\Manager;
use App\Strategies\Employment\ManagerEmploymentStrategy;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagerEmploymentStrategyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function employing_an_injured_manager_throws_an_exception()
    {
        $this->expectException(CannotBeEmployedException::class);
        $this->withoutExceptionHandling();

        $manager = Manager::factory()->injured()->create();

        (new ManagerEmploymentStrategy($manager))->employ();
    }

    /**
     * @test
     */
    public function a_manager_employed_in_the_future_has_future_employment()
    {
        $manager = Manager::factory()->create();

        $manager->employ(Carbon::tomorrow());

        $this->assertTrue($manager->hasFutureEmployment());
    }

    /**
     * @test
     */
    public function a_single_roster_member_can_be_employed_default_to_now()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $manager = Manager::factory()->create();

        $manager->employ();

        $this->assertCount(1, $manager->employments);
        $this->assertEquals($now->toDateTimeString(), $manager->currentEmployment->started_at);
    }

    /**
     * @test
     */
    public function a_single_roster_member_can_be_employed_at_start_date()
    {
        $yesterday = Carbon::yesterday();
        Carbon::setTestNow($yesterday);

        $manager = Manager::factory()->create();

        $manager->employ($yesterday);

        $this->assertEquals($yesterday->toDateTimeString(), $manager->currentEmployment->started_at);
    }

    /**
     * @test
     */
    public function a_single_roster_member_with_an_employment_in_the_future_can_be_employed_at_start_date()
    {
        $today = Carbon::today();
        Carbon::setTestNow($today);

        $manager = Manager::factory()->create();
        $manager->employments()->create(['started_at' => Carbon::tomorrow()]);

        $manager->employ($today);

        $this->assertEquals($today->toDateTimeString(), $manager->currentEmployment->started_at);
    }
}
