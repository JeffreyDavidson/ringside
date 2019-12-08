<?php

namespace Tests\Feature\Admin\Manager;

use Tests\TestCase;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group admins
 * @group roster
 */
class EmployManagerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_employ_a_pending_employment_manager()
    {
        $this->withoutExceptionHandling();
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('pending-employment')->create();

        $response = $this->employRequest($manager);

        $response->assertRedirect(route('managers.index'));
        tap($manager->fresh(), function (Manager $manager) {
            $this->assertTrue($manager->is_employed);
        });
    }
}
