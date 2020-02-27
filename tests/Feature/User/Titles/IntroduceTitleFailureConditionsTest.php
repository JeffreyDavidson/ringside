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
}
