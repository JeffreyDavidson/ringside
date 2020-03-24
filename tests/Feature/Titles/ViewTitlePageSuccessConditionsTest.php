<?php

namespace Tests\Feature\Titles;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\TitleFactory;

/**
 * @group titles
 */
class ViewTitlePageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_view_a_title_page()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->create();

        $response = $this->showRequest($title);

        $response->assertViewIs('titles.show');
        $this->assertTrue($response->data('title')->is($title));
    }

    /** @test */
    public function a_titles_data_can_be_seen_on_the_title_page()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->create(['name' => 'Title 1']);

        $response = $this->showRequest($title);

        $response->assertSee('Title 1');
    }
}
