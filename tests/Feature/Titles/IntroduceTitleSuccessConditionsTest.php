<?php

namespace Tests\Feature\Titles;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\TitleFactory;
use Tests\TestCase;

/**
 * @group titles
 */
class IntroduceTitleSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_introduce_a_pending_introduction_title()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->pendingIntroduction()->create();

        $response = $this->introduceRequest($title);

        $response->assertRedirect(route('titles.index'));
        tap($title->fresh(), function ($title) {
            $this->assertTrue($title->isCompetable());
        });
    }
}
