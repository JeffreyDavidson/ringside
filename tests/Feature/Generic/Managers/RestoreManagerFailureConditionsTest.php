<?php

namespace Tests\Feature\Generic\Managers;

use Tests\TestCase;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group generics
 * @group roster
 */
class RestoreManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_available_manager_cannot_be_restored()
    {
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('available')->create();

        $response = $this->restoreRequest($manager);

        $response->assertNotFound();
    }

    /** @test */
    public function a_pending_employment_manager_cannot_be_restored()
    {
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('pending-employment')->create();

        $response = $this->restoreRequest($manager);

        $response->assertNotFound();
    }

    /** @test */
    public function an_injured_manager_cannot_be_restored()
    {
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('injured')->create();

        $response = $this->restoreRequest($manager);

        $response->assertNotFound();
    }

    /** @test */
    public function a_suspended_manager_cannot_be_restored()
    {
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('suspended')->create();

        $response = $this->restoreRequest($manager);

        $response->assertNotFound();
    }

    /** @test */
    public function a_retired_manager_cannot_be_restored()
    {
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('retired')->create();

        $response = $this->restoreRequest($manager);

        $response->assertNotFound();
    }
}
