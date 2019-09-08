<?php

namespace Tests\Feature\Admin\Manager;

use Tests\TestCase;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group admins
 */
class EmployManagerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_activate_a_pending_introduction_manager()
    {
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('pending-introduction')->create();

        $response = $this->put(route('managers.employ', $manager));

        $response->assertRedirect(route('managers.index'));
        tap($manager->fresh(), function (Manager $manager) {
            $this->assertTrue($manager->is_bookable);
        });
    }
}
