<?php

namespace Tests\Feature\Titles;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group titles
 */
class ViewTitlesListFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_basic_user_cannot_view_titles_page()
    {
        $this->actAs(Role::BASIC);

        $response = $this->indexRequest('titles');

        $response->assertForbidden();
    }

    /** @test */
    public function a_guest_cannot_view_titles_page()
    {
        $response = $this->indexRequest('title');

        $response->assertRedirect(route('login'));
    }
}
