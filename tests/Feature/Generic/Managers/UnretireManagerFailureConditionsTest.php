<?php

namespace Tests\Feature\Generic\Managers;

use Tests\TestCase;
use App\Models\Manager;
use App\Exceptions\CannotBeUnretiredException;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group generics
 * @group roster
 */
class UnretireManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_available_manager_cannot_be_unretired()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeUnretiredException::class);

        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('available')->create();

        $response = $this->unretireRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_suspended_manager_cannot_be_unretired()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeUnretiredException::class);

        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('suspended')->create();

        $response = $this->unretireRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function an_injured_manager_cannot_be_unretired()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeUnretiredException::class);

        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('injured')->create();

        $response = $this->unretireRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_employment_manager_cannot_be_unretired()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeUnretiredException::class);

        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('pending-employment')->create();

        $response = $this->unretireRequest($manager);

        $response->assertForbidden();
    }
}
