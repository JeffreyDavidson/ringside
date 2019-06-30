<?php

namespace Tests\Feature\Titles;

use Tests\TestCase;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewActiveTitlesListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_view_all_active_titles()
    {
        $this->actAs('administrator');
        $activeTitles = factory(Title::class, 3)->states('active')->create();
        $inactive = factory(Title::class)->states('inactive')->create();

        $response = $this->get(route('titles.index'));
        $responseAjax = $this->getJson(route('titles.index', ['status' => 'only_active']), ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertOk();
        $response->assertViewIs('titles.index');
        $responseAjax->assertJson([
            'recordsTotal' => $activeTitles->count(),
            'data'         => $activeTitles->map(function (Title $title) {
                return ['id' => $title->id, 'name' => e($title->name)];
            })->toArray(),
        ]);
    }

    /** @test */
    public function a_basic_user_cannot_view_all_active_titles()
    {
        $this->actAs('basic-user');
        $title = factory(Title::class)->states('active')->create();

        $response = $this->get(route('titles.index', ['status' => 'only_active']));

        $response->assertStatus(403);
    }

    /** @test */
    public function a_guest_cannot_view_all_active_titles()
    {
        $title = factory(Title::class)->states('active')->create();

        $response = $this->get(route('titles.index', ['status' => 'only_active']));

        $response->assertRedirect(route('login'));
    }
}
