<?php

namespace Tests\Feature\Guest\Titles;

use Tests\TestCase;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $title = factory(Title::class)->states('pending-introduction')->create();

        $response = $this->introduceRequest($title);

        $response->assertRedirect(route('login'));
    }
}
