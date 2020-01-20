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
class EmployManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * A 'available' manager must already be employed to be available, so this should fail
     */
    public function an_available_manager_cannot_be_employed()
    {
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('available')->create();

        $response = $this->employRequest($manager);

        $response->assertSessionHasErrors('started_at');
    }

    /**
     * @test
     * A 'retired' manager must already be employed to be available, so this should fail
     */
    public function a_retired_manager_cannot_be_employed()
    {
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('retired')->create();

        $response = $this->employRequest($manager);

        $response->assertSessionHasErrors('started_at');
    }

    /**
     * @test
     * A 'suspended' manager must already be employed to be available, so this should fail
     */
    public function a_suspended_manager_cannot_be_employed()
    {
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('suspended')->create();

        $response = $this->employRequest($manager);

        $response->assertSessionHasErrors('started_at');
    }

    /**
     * @test
     * An 'injured' manager must already be employed to be available, so this should fail
     */
    public function an_injured_manager_cannot_be_employed()
    {
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('injured')->create();

        $response = $this->employRequest($manager);

        $response->assertSessionHasErrors('started_at');
    }
}
