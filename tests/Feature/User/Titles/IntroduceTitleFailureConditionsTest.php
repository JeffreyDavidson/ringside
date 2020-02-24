<?php

namespace Tests\Feature\User\Titles;

use Tests\TestCase;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $title = factory(Title::class)->states('pending-introduction')->create();

        $response = $this->put(route('titles.introduce', $title));

        $response->assertForbidden();
    }
}
