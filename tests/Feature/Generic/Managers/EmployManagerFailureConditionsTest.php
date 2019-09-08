<?php

namespace Tests\Feature\Generic\Manager;

use Tests\TestCase;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group generics
 */
class EmployManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** 
     * @test 
     * A 'bookable' manager must already be employed to be bookable, so this should fail
     */
    public function a_bookable_manager_cannot_be_employed()
    {
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('bookable')->create();

        $response = $this->put(route('managers.employ', $manager));

        $response->assertForbidden();
    }

    /** 
     * @test 
     * A 'retired' manager must already be employed to be bookable, so this should fail 
     */
    public function a_retired_manager_cannot_be_employed()
    {
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('retired')->create();

        $response = $this->put(route('managers.employ', $manager));

        $response->assertForbidden();
    }

    /** 
     * @test 
     * A 'suspended' manager must already be employed to be bookable, so this should fail
     */
    public function a_suspended_manager_cannot_be_employed()
    {
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('suspended')->create();

        $response = $this->put(route('managers.employ', $manager));

        $response->assertForbidden();
    }

    /** 
     * @test 
     * An 'injured' manager must already be employed to be bookable, so this should fail
     */
    public function an_injured_manager_cannot_be_employed()
    {
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('injured')->create();

        $response = $this->put(route('managers.employ', $manager));

        $response->assertForbidden();
    }
}
