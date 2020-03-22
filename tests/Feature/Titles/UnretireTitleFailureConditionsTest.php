<?php

namespace Tests\Feature\User\Titles;

use App\Enums\Role;
use App\Exceptions\CannotBeUnretiredException;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\TitleFactory;
use Tests\TestCase;

/**
 * @group titles
 * @group users
 */
class UnretireTitleFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_unretire_a_retired_title()
    {
        $this->actAs(Role::BASIC);
        $title = TitleFactory::new()->retired()->create();

        $response = $this->unretireRequest($title);

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_unretire_a_retired_title()
    {
        $title = factory(Title::class)->states('retired')->create();

        $response = $this->unretireRequest($title);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_competable_title_cannot_unretire()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeUnretiredException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->competable()->create();

        $response = $this->unretireRequest($title);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_introduction_title_cannot_unretire()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeUnretiredException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->pendingIntroduction()->create();

        $response = $this->unretireRequest($title);

        $response->assertForbidden();
    }
}
