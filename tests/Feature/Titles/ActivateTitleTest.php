<?php

namespace Tests\Feature\Titles;

use App\Enums\Role;
use App\Exceptions\CannotBeActivatedException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\TitleFactory;

/**
 * @group titles
 */
class ActivateTitleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_activate_a_future_activation_title()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->futureActivation()->create();

        $response = $this->activateRequest($title);

        $response->assertRedirect(route('titles.index'));
        tap($title->fresh(), function ($title) {
            $this->assertTrue($title->isCurrentlyActivated());
        });
    }

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
        dd($title);

        $this->activateRequest($title);
    }
}
