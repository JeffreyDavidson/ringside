<?php

namespace Tests\Feature\Titles;

use App\Enums\Role;
use App\Exceptions\CannotBeActivatedException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\TitleFactory;
use Tests\TestCase;

/**
 * @group titles
 */
class ActivateTitleFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_activate_a_future_activation_title()
    {
        $this->actAs(Role::BASIC);
        $title = TitleFactory::new()->futureActivation()->create();

        $response = $this->activateRequest($title);

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_activate_a_future_activation_title()
    {
        $title = TitleFactory::new()->futureActivation()->create();

        $response = $this->activateRequest($title);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function an_active_title_cannot_be_activated()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeActivatedException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->active()->create();

        $this->activateRequest($title);
    }
}
