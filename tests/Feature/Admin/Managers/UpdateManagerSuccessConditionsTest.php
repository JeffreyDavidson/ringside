<?php

namespace Tests\Feature\Admin\Managers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\ManagerFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group admins
 * @group roster
 */
class UpdateManagerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Valid parameters for request.
     *
     * @param  array $overrides
     * @return array
     */
    private function validParams($overrides = [])
    {
        return array_replace([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'started_at' => now()->toDateTimeString(),
        ], $overrides);
    }

    /** @test */
    public function an_administrator_can_view_the_form_for_editing_a_manager()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->create();

        $response = $this->editRequest($manager);

        $response->assertViewIs('managers.edit');
        $this->assertTrue($response->data('manager')->is($manager));
    }

    /** @test */
    public function an_administrator_can_update_a_manager()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->create();

        $response = $this->updateRequest($manager, $this->validParams());

        $response->assertRedirect(route('managers.index'));
        tap($manager->fresh(), function ($manager) {
            $this->assertEquals('John', $manager->first_name);
            $this->assertEquals('Smith', $manager->last_name);
        });
    }
}
