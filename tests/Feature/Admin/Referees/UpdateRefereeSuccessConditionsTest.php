<?php

namespace Tests\Feature\Admin\Referees;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group admins
 * @group roster
 */
class UpdateRefereeSuccessConditionsTest extends TestCase
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
    public function an_administrator_can_view_the_form_for_editing_a_referee()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->create();

        $response = $this->editRequest($referee);

        $response->assertViewIs('referees.edit');
        $this->assertTrue($response->data('referee')->is($referee));
    }

    /** @test */
    public function an_administrator_can_update_a_referee()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->create();

        $response = $this->updateRequest($referee, $this->validParams());

        $response->assertRedirect(route('referees.index'));
        tap($referee->fresh(), function ($referee) {
            $this->assertEquals('John', $referee->first_name);
            $this->assertEquals('Smith', $referee->last_name);
        });
    }
}
