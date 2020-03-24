<?php

namespace Tests\Feature\Titles;

use App\Enums\Role;
use App\Exceptions\CannotBeIntroducedException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\TitleFactory;
use Tests\TestCase;

/**
 * @group titles
 */
class IntroduceTitleFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_introduce_a_pending_introduction_title()
    {
        $this->actAs(Role::BASIC);
        $title = TitleFactory::new()->pendingIntroduction()->create();

        $response = $this->introduceRequest($title);

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_introduce_a_pending_introduction_title()
    {
        $title = TitleFactory::new()->pendingIntroduction()->create();

        $response = $this->introduceRequest($title);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function a_competable_title_cannot_be_introduced()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeIntroducedException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->competable()->create();

        $response = $this->introduceRequest($title);

        $response->assertForbidden();
    }
}
