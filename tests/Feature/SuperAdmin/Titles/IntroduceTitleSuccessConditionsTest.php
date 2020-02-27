<?php

namespace Tests\Feature\SuperAdmin\Titles;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\TitleFactory;

/**
 * @group titles
 * @group superadmins
 */
class IntroduceTitleSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_introduce_a_pending_introduction_title()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $title = TitleFactory::new()->pendingIntroduction()->create();

        $response = $this->introduceRequest($title);

        $response->assertRedirect(route('titles.index'));
        tap($title->fresh(), function ($title) {
            $this->assertTrue($title->isCompetable());
        });
    }
}
