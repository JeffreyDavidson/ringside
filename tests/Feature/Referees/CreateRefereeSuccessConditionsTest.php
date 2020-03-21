<?php

namespace Tests\Feature\Referees;

use App\Enums\Role;
use App\Models\Referee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group referees
 * @group roster
 */
class CreateRefereeSuccessConditionsTest extends TestCase
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

    /**
     * @test
     * @dataProvider adminRoles
     */
    public function administrators_can_view_the_form_for_creating_a_referee($adminRoles)
    {
        $this->actAs($adminRoles);

        $response = $this->createRequest('referee');

        $response->assertViewIs('referees.create');
        $response->assertViewHas('referee', new Referee);
    }

    /**
     * @test
     * @dataProvider adminRoles
     */
    public function administrators_can_create_a_referee($adminRoles)
    {
        $this->actAs($adminRoles);

        $response = $this->storeRequest('referee', $this->validParams());

        $response->assertRedirect(route('referees.index'));
    }

    public function adminRoles()
    {
        return [
            [Role::ADMINISTRATOR],
            [Role::SUPER_ADMINISTRATOR],
        ];
    }
}
