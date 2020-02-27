<?php

namespace Tests\Feature\Generic\Titles;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\TitleFactory;

/**
 * @group titles
 * @group generics
 */
class ViewTitlePageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_titles_data_can_be_seen_on_the_title_page()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->create(['name' => 'Title 1']);

        $response = $this->showRequest($title);

        $response->assertSee('Title 1');
    }
}
