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
class ViewTitlePageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_view_a_title_page()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $title = TitleFactory::new()->create();

        $response = $this->showRequest($title);

        $response->assertViewIs('titles.show');
        $this->assertTrue($response->data('title')->is($title));
    }
}
