<?php

namespace Tests\Feature\Guest\Titles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\TitleFactory;
use Tests\TestCase;

/**
 * @group titles
 * @group guests
 */
class IntroduceTitleFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cannot_introduce_a_pending_introduction_title()
    {
        $title = TitleFactory::new()->pendingIntroduction()->create();

        $response = $this->introduceRequest($title);

        $response->assertRedirect(route('login'));
    }
}
