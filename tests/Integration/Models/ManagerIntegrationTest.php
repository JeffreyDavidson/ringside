<?php

namespace Tests\Integration\Models;

use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group managers
 */
class ManagerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_get_available_managers()
    {
        $futureEmployedManager = Manager::factory()->withFutureEmployment()->create();
        $availableManager = Manager::factory()->available()->create();
        $injuredManager = Manager::factory()->injured()->create();
        $suspendedManager = Manager::factory()->suspended()->create();
        $retiredManager = Manager::factory()->retired()->create();
        $releasedManager = Manager::factory()->released()->create();

        $availableManagers = Manager::available()->get();

        $this->assertCount(1, $availableManagers);
        $this->assertTrue($availableManagers->contains($availableManager));
        $this->assertFalse($availableManagers->contains($futureEmployedManager));
        $this->assertFalse($availableManagers->contains($injuredManager));
        $this->assertFalse($availableManagers->contains($suspendedManager));
        $this->assertFalse($availableManagers->contains($retiredManager));
        $this->assertFalse($availableManagers->contains($releasedManager));
    }

    /**
     * @test
     */
    public function it_can_get_future_employed_managers()
    {
        $futureEmployedManager = Manager::factory()->withFutureEmployment()->create();
        $availableManager = Manager::factory()->available()->create();
        $injuredManager = Manager::factory()->injured()->create();
        $suspendedManager = Manager::factory()->suspended()->create();
        $retiredManager = Manager::factory()->retired()->create();
        $releasedManager = Manager::factory()->released()->create();

        $futureEmployedManagers = Manager::futureEmployed()->get();

        $this->assertCount(1, $futureEmployedManagers);
        $this->assertTrue($futureEmployedManagers->contains($futureEmployedManager));
        $this->assertFalse($futureEmployedManagers->contains($availableManager));
        $this->assertFalse($futureEmployedManagers->contains($injuredManager));
        $this->assertFalse($futureEmployedManagers->contains($suspendedManager));
        $this->assertFalse($futureEmployedManagers->contains($retiredManager));
        $this->assertFalse($futureEmployedManagers->contains($releasedManager));
    }

    /**
     * @test
     */
    public function it_can_get_employed_managers()
    {
        $futureEmployedManager = Manager::factory()->withFutureEmployment()->create();
        $availableManager = Manager::factory()->available()->create();
        $injuredManager = Manager::factory()->injured()->create();
        $suspendedManager = Manager::factory()->suspended()->create();
        $retiredManager = Manager::factory()->retired()->create();
        $releasedManager = Manager::factory()->released()->create();

        $employedManagers = Manager::employed()->get();

        $this->assertCount(3, $employedManagers);
        $this->assertTrue($employedManagers->contains($injuredManager));
        $this->assertTrue($employedManagers->contains($availableManager));
        $this->assertTrue($employedManagers->contains($suspendedManager));
        $this->assertFalse($employedManagers->contains($futureEmployedManager));
        $this->assertFalse($employedManagers->contains($retiredManager));
        $this->assertFalse($employedManagers->contains($releasedManager));
    }

    /**
     * @test
     */
    public function it_can_get_released_managers()
    {
        $futureEmployedManager = Manager::factory()->withFutureEmployment()->create();
        $availableManager = Manager::factory()->available()->create();
        $injuredManager = Manager::factory()->injured()->create();
        $suspendedManager = Manager::factory()->suspended()->create();
        $retiredManager = Manager::factory()->retired()->create();
        $releasedManager = Manager::factory()->released()->create();

        $releasedManagers = Manager::released()->get();

        $this->assertCount(1, $releasedManagers);
        $this->assertTrue($releasedManagers->contains($releasedManager));
        $this->assertFalse($releasedManagers->contains($futureEmployedManager));
        $this->assertFalse($releasedManagers->contains($availableManager));
        $this->assertFalse($releasedManagers->contains($injuredManager));
        $this->assertFalse($releasedManagers->contains($suspendedManager));
        $this->assertFalse($releasedManagers->contains($retiredManager));
    }

    /**
     * @test
     */
    public function it_can_get_suspended_managers()
    {
        $suspendedManager = Manager::factory()->suspended()->create();
        $futureEmployedManager = Manager::factory()->withFutureEmployment()->create();
        $availbleManager = Manager::factory()->available()->create();
        $injuredManager = Manager::factory()->injured()->create();
        $retiredManager = Manager::factory()->retired()->create();
        $releasedManager = Manager::factory()->released()->create();

        $suspendedManagers = Manager::suspended()->get();

        $this->assertCount(1, $suspendedManagers);
        $this->assertTrue($suspendedManagers->contains($suspendedManager));
        $this->assertFalse($suspendedManagers->contains($futureEmployedManager));
        $this->assertFalse($suspendedManagers->contains($availbleManager));
        $this->assertFalse($suspendedManagers->contains($injuredManager));
        $this->assertFalse($suspendedManagers->contains($retiredManager));
        $this->assertFalse($suspendedManagers->contains($releasedManager));
    }

    /**
     * @test
     */
    public function it_can_get_injured_managers()
    {
        $injuredManager = Manager::factory()->injured()->create();
        $futureEmployedManager = Manager::factory()->withFutureEmployment()->create();
        $availableManager = Manager::factory()->available()->create();
        $suspendedManager = Manager::factory()->suspended()->create();
        $retiredManager = Manager::factory()->retired()->create();
        $releasedManager = Manager::factory()->released()->create();

        $injuredManagers = Manager::injured()->get();

        $this->assertCount(1, $injuredManagers);
        $this->assertTrue($injuredManagers->contains($injuredManager));
        $this->assertFalse($injuredManagers->contains($futureEmployedManager));
        $this->assertFalse($injuredManagers->contains($availableManager));
        $this->assertFalse($injuredManagers->contains($suspendedManager));
        $this->assertFalse($injuredManagers->contains($retiredManager));
        $this->assertFalse($injuredManagers->contains($releasedManager));
    }

    /**
     * @test
     */
    public function it_can_get_retired_managers()
    {
        $retiredManager = Manager::factory()->retired()->create();
        $futureEmployedManager = Manager::factory()->withFutureEmployment()->create();
        $availableManager = Manager::factory()->available()->create();
        $injuredManager = Manager::factory()->injured()->create();
        $suspendedManager = Manager::factory()->suspended()->create();

        $retiredManagers = Manager::retired()->get();

        $this->assertCount(1, $retiredManagers);
        $this->assertTrue($retiredManagers->contains($retiredManager));
        $this->assertFalse($retiredManagers->contains($futureEmployedManager));
        $this->assertFalse($retiredManagers->contains($availableManager));
        $this->assertFalse($retiredManagers->contains($injuredManager));
        $this->assertFalse($retiredManagers->contains($suspendedManager));
    }
}
