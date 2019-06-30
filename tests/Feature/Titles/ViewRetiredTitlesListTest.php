<?php

namespace Tests\Feature\Titles;

use Tests\TestCase;
use App\Models\Title;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewRetiredTitlesListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_view_all_retired_titles()
    {
        $this->actAs('administrator');
        $retiredTitles = factory(Title::class, 3)->states('retired')->create();
        factory(Title::class)->states('active')->create();

        $response = $this->get(route('titles.index'));
        $responseAjax = $this->getJson(route('titles.index', ['status' => 'only_retired']), ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertOk();
        $response->assertViewIs('titles.index');
        $responseAjax->assertJson([
            'recordsTotal' => $retiredTitles->count(),
            'data'         => $retiredTitles->map(function (Title $title) {
                return ['id' => $title->id, 'name' => e($title->name)];
            })->toArray(),
        ]);
    }

    /** @test */
    public function a_basic_user_cannot_view_all_retired_titles()
    {
        $this->actAs('basic-user');
        $title = factory(Title::class)->states('retired')->create();

        $response = $this->get(route('titles.index', ['state' => 'retired']));

        $response->assertStatus(403);
    }

    /** @test */
    public function a_guest_cannot_view_all_retired_titles()
    {
        $title = factory(Title::class)->states('retired')->create();

        $response = $this->get(route('titles.index', ['state' => 'retired']));

        $response->assertRedirect(route('login'));
    }
}
