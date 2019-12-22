<?php

namespace Tests\Feature\Generic\Managers;

use Tests\TestCase;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Exceptions\CannotBeMarkedAsHealedException;

/**
 * @group managers
 * @group generics
 * @group roster
 */
class HealManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_available_manager_cannot_be_marked_as_healed_from_an_injury()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeMarkedAsHealedException::class);

        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('available')->create();

        $response = $this->markAsHealedRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_employment_manager_cannot_be_marked_as_healed_from_an_injury()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeMarkedAsHealedException::class);

        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('pending-employment')->create();

        $response = $this->markAsHealedRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_retired_manager_cannot_be_marked_as_healed_from_an_injury()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeMarkedAsHealedException::class);

        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('retired')->create();

        $response = $this->markAsHealedRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_suspended_manager_cannot_be_marked_as_healed_from_an_injury()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeMarkedAsHealedException::class);

        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('suspended')->create();

        $response = $this->markAsHealedRequest($manager);

        $response->assertForbidden();
    }
}
