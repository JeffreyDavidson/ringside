<?php

namespace Tests\Feature\Admin\Manager;

use App\Enums\Role;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group managers
 * @group admins
 * @group roster
 */
class CreateManagerSuccessConditionsTest extends TestCase
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
    public function an_administrator_can_view_the_form_for_creating_a_manager()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->createRequest('manager');

        $response->assertViewIs('managers.create');
        $response->assertViewHas('manager', new Manager);
    }

    /** @test */
    public function an_administrator_can_create_a_manager()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->storeRequest('manager', $this->validParams());

        $response->assertRedirect(route('managers.index'));
    }
}
