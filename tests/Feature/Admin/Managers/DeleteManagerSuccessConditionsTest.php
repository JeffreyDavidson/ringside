<?php

namespace Tests\Feature\Admin\Managers;

use Tests\TestCase;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group admins
 * @group roster
 */
class DeleteManagerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_delete_an_available_manager()
    {
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('available')->create();

        $response = $this->deleteRequest($manager);

        $response->assertRedirect(route('managers.index'));
        $this->assertSoftDeleted('managers', [
            'id' => $manager->id,
            'first_name' => $manager->first_name,
            'last_name' => $manager->last_name
        ]);
    }

    /** @test */
    public function an_administrator_can_delete_a_pending_employment_manager()
    {
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('pending-employment')->create();

        $response = $this->deleteRequest($manager);

        $response->assertRedirect(route('managers.index'));
        $this->assertSoftDeleted('managers', [
            'id' => $manager->id,
            'first_name' => $manager->first_name,
            'last_name' => $manager->last_name
        ]);
    }

    /** @test */
    public function an_administrator_can_delete_a_retired_manager()
    {
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('retired')->create();

        $response = $this->deleteRequest($manager);

        $response->assertRedirect(route('managers.index'));
        $this->assertSoftDeleted('managers', [
            'id' => $manager->id,
            'first_name' => $manager->first_name,
            'last_name' => $manager->last_name
        ]);
    }

    /** @test */
    public function an_administrator_can_delete_a_suspended_manager()
    {
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('suspended')->create();

        $response = $this->deleteRequest($manager);

        $response->assertRedirect(route('managers.index'));
        $this->assertSoftDeleted('managers', [
            'id' => $manager->id,
            'first_name' => $manager->first_name,
            'last_name' => $manager->last_name
        ]);
    }

    /** @test */
    public function an_administrator_can_delete_an_injured_manager()
    {
        $this->actAs('administrator');
        $manager = factory(Manager::class)->states('injured')->create();

        $response = $this->deleteRequest($manager);

        $response->assertRedirect(route('managers.index'));
        $this->assertSoftDeleted('managers', [
            'id' => $manager->id,
            'first_name' => $manager->first_name,
            'last_name' => $manager->last_name
        ]);
    }
}
