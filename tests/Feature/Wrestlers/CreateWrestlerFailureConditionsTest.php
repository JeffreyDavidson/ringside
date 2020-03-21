<?php

namespace Tests\Feature\User\Wrestlers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group users
 * @group roster
 */
class CreateWrestlerFailureConditionsTest extends TestCase
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
            'name' => 'Example Wrestler Name',
            'feet' => '6',
            'inches' => '4',
            'weight' => '240',
            'hometown' => 'Laraville, FL',
            'signature_move' => 'The Finisher',
            'started_at' => now()->toDateTimeString(),
        ], $overrides);
    }

    /** @test */
    public function a_basic_user_cannot_view_the_form_for_creating_a_wrestler()
    {
        $this->actAs(Role::BASIC);

        $response = $this->createRequest('wrestler');

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_create_a_wrestler()
    {
        $this->actAs(Role::BASIC);

        $response = $this->storeRequest('wrestler', $this->validParams());

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_view_the_form_for_creating_a_wrestler()
    {
        $response = $this->createRequest('wrestler');

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_create_a_wrestler()
    {
        $response = $this->storeRequest('wrestler', $this->validParams());

        $response->assertRedirect(route('login'));
    }
}
