<?php

namespace Tests\Feature\User\Titles;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group titles
 * @group users
 */
class CreateTitleFailureConditionsTest extends TestCase
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
            'name' => 'Example Name Title',
            'introduced_at' => now()->toDateTimeString(),
        ], $overrides);
    }

    /** @test */
    public function a_basic_user_cannot_view_the_form_for_creating_a_title()
    {
        $this->actAs(Role::BASIC);

        $response = $this->createRequest('titles');

        $response->assertForbidden();
    }

    /** @test */
    public function a_basic_user_cannot_create_a_title()
    {
        $this->actAs(Role::BASIC);

        $response = $this->storeRequest('titles', $this->validParams());

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_view_the_form_for_creating_a_title()
    {
        $response = $this->createRequest('titles');

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_create_a_title()
    {
        $response = $this->storeRequest('title', $this->validParams());

        $response->assertRedirect(route('login'));
    }
}
