<?php

namespace Tests\Feature\Titles;

use App\Enums\Role;
use App\Exceptions\CannotBeRetiredException;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\TitleFactory;
use Tests\TestCase;

/**
 * @group titles
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

    /** @test */
    public function a_retired_title_cannot_be_retired_again()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeRetiredException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->retired()->create();

        $response = $this->retireRequest($title);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_introduction_title_cannot_be_retired()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeRetiredException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->pendingIntroduction()->create();

        $response = $this->retireRequest($title);

        $response->assertForbidden();
    }
}
