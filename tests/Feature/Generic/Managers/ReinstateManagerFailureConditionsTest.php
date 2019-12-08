<?php

namespace Tests\Feature\Generic\Managers;

use Tests\TestCase;
use App\Models\Manager;
use App\Exceptions\CannotBeReinstatedException;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group generics
 * @group roster
 */
class ReinstateManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_available_manager_cannot_be_reinstated()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeReinstatedException::class);

        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('available')->create();

        $response = $this->reinstateRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_employment_manager_cannot_be_reinstated()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeReinstatedException::class);

        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('pending-employment')->create();

        $response = $this->reinstateRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function a_retired_manager_cannot_be_reinstated()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeReinstatedException::class);

        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('retired')->create();

        $response = $this->reinstateRequest($manager);

        $response->assertForbidden();
    }

    /** @test */
    public function an_injured_manager_cannot_be_reinstated()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeReinstatedException::class);

        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('injured')->create();

        $response = $this->reinstateRequest($manager);

        $response->assertForbidden();
    }
}
