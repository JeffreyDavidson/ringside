<?php

namespace Tests\Feature\User\Titles;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\TitleFactory;

/**
 * @group titles
 * @group users
 */
class RetireTitleFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_retire_a_competable_title()
    {
        $this->actAs(Role::BASIC);
        $title = TitleFactory::new()->competable()->create();

        $response = $this->retireRequest($title);

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_retire_a_competable_title()
    {
        $title = factory(Title::class)->states('competable')->create();

        $response = $this->retireRequest($title);

        $response->assertRedirect(route('login'));
    }
}
