<?php

namespace Tests\Feature\User\Wrestlers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\WrestlerFactory;

/**
 * @group wrestlers
 * @group users
 * @group roster
 */
class UpdateWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Default attributes for model.
     *
     * @param  array  $overrides
     * @return array
     */
    private function oldAttributes($overrides = [])
    {
        return array_replace([
            'name' => 'Old Wrestler Name',
            'height' => 73,
            'weight' => 240,
            'hometown' => 'Old City, State',
            'signature_move' => 'Old Finisher',
        ], $overrides);
    }

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
    public function a_basic_user_cannot_view_the_form_for_editing_a_wrestler()
    {
        $this->actAs(Role::BASIC);
        $wrestler = WrestlerFactory::new()->create();

        $response = $this->editRequest($wrestler);

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_update_a_wrestler()
    {
        $this->actAs(Role::BASIC);
        $wrestler = WrestlerFactory::new()->create();

        $response = $this->updateRequest($wrestler, $this->validParams());

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_view_the_form_for_editing_a_wrestler()
    {
        $wrestler = WrestlerFactory::new()->create($this->oldAttributes());

        $response = $this->editRequest($wrestler);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_update_a_wrestler()
    {
        $wrestler = WrestlerFactory::new()->create($this->oldAttributes());

        $response = $this->updateRequest($wrestler, $this->validParams());

        $response->assertRedirect(route('login'));
    }
}
