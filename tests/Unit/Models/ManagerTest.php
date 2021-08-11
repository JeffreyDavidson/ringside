<?php

namespace Tests\Unit\Models;

use App\Enums\ManagerStatus;
use App\Models\Manager;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group managers
 * @group roster
 * @group models
 */
class ManagerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_manager_has_a_first_name()
    {
        $manager = new Manager(['first_name' => 'John']);

        $this->assertEquals('John', $manager->first_name);
    }

    /**
     * @test
     */
    public function a_manager_has_a_last_name()
    {
        $manager = new Manager(['last_name' => 'Smith']);

        $this->assertEquals('Smith', $manager->last_name);
    }

    /**
     * @test
     */
    public function a_manager_has_a_status()
    {
        $manager = new Manager();
        $manager->setRawAttributes(['status' => 'example'], true);

        $this->assertEquals('example', $manager->getRawOriginal('status'));
    }

    /**
     * @test
     */
    public function a_manager_status_gets_cast_as_a_manager_status_enum()
    {
        $manager = new Manager();

        $this->assertInstanceOf(ManagerStatus::class, $manager->status);
    }

    /**
     * @test
     */
    public function a_manager_is_a_single_roster_member()
    {
        $this->assertEquals(SingleRosterMember::class, get_parent_class(Manager::class));
    }

    /**
     * @test
     */
    public function a_manager_uses_has_a_unguarded_trait()
    {
        $this->assertUsesTrait('App\Models\Concerns\Unguarded', Manager::class);
    }

    /**
     * @test
     */
    public function a_manager_uses_soft_deleted_trait()
    {
        $this->assertUsesTrait('Illuminate\Database\Eloquent\SoftDeletes', Manager::class);
    }

    /**
     * @test
     */
    public function a_manager_uses_has_a_full_name_trait()
    {
        $this->assertUsesTrait('App\Models\Concerns\HasFullName', Manager::class);
    }

    /**
     * @test
     */
    public function a_manager_with_a_suspension_is_suspended()
    {
        $manager = Manager::factory()->suspended()->create();

        $this->assertTrue($manager->isSuspended());
    }

    /**
     * @test
     */
    public function a_manager_can_be_suspended_multiple_times()
    {
        $manager = Manager::factory()->suspended()->create();

        $manager->reinstate();
        $manager->suspend();

        $this->assertCount(1, $manager->previousSuspensions);
    }

    /**
     * @test
     */
    public function a_manager_with_a_retirement_is_retired()
    {
        $manager = Manager::factory()->retired()->create();

        $this->assertTrue($manager->isRetired());
    }

    /**
     * @test
     */
    public function a_manager_with_an_injury_is_injured()
    {
        $manager = Manager::factory()->injured()->create();

        $this->assertTrue($manager->isInjured());
    }

    /**
     * @test
     */
    public function a_manager_can_be_injured_multiple_times()
    {
        $manager = Manager::factory()->injured()->create();

        $manager->clearFromInjury();
        $manager->injure();

        $this->assertCount(1, $manager->previousInjuries);
    }

    /**
     * @test
     */
    public function a_manager_with_an_employment_now_or_in_the_past_is_employed()
    {
        $manager = Manager::factory()->create();
        $manager->employments()->create(['started_at' => Carbon::now()]);

        $this->assertTrue($manager->isCurrentlyEmployed());
    }

    /**
     * @test
     */
    public function a_manager_without_an_employment_is_unemployed()
    {
        $manager = Manager::factory()->create();

        $this->assertTrue($manager->isUnemployed());
    }
}
