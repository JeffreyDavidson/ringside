<?php

namespace Tests\Unit\Models;

use Tests\TestCase;

class SingleRosterMemberTest extends TestCase
{
    /** @test */
    public function a_single_roster_member_can_be_employed_default_to_now()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $manager = factory(Manager::class)->create();

        $manager->employ();

        $this->assertCount(1, $manager->employments);
        $this->assertEquals($now->toDateTimeString(), $manager->currentEmployment->started_at);
    }

    /** @test */
    public function a_single_roster_member_can_be_employed_at_start_date()
    {
        $yesterday = Carbon::yesterday();
        Carbon::setTestNow($yesterday);

        $manager = factory(Manager::class)->create();

        $manager->employ($yesterday);

        $this->assertEquals($yesterday->toDateTimeString(), $manager->currentEmployment->started_at);
    }

    /** @test */
    public function a_single_roster_member_can_be_employed_in_the_future()
    {
        $tomorrow = Carbon::tomorrow();
        Carbon::setTestNow($tomorrow);

        $manager = factory(Manager::class)->create();

        $manager->employ($tomorrow);

        $this->assertEquals($tomorrow->toDateTimeString(), $manager->currentEmployment->started_at);
    }

    /** @test */
    public function a_single_roster_member_with_an_employment_in_the_future_can_be_employed_at_start_date()
    {
        $today = Carbon::today();
        Carbon::setTestNow($today);

        $manager = factory(Manager::class)->create();
        $manager->employments()->create(['started_at' => Carbon::tomorrow()]);

        $manager->employ($today);

        $this->assertEquals($today->toDateTimeString(), $manager->currentEmployment->started_at);
    }
}
