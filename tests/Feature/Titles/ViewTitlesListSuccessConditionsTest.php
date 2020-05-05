<?php

namespace Tests\Feature\Titles;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\TitleFactory;

/**
 * @group titles
 */
class ViewTitlesListSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @var \Illuminate\Support\Collection */
    protected $titles;

    /** @test */
    public function an_administrator_can_view_titles_page()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->indexRequest('titles');

        $response->assertOk();
        $response->assertViewIs('titles.index');
    }

    /** @test */
    public function an_administrator_can_view_active_titles()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $responseAjax = $this->ajaxJson(route('titles.index'));

        $responseAjax->assertJson([
            'recordsTotal' => $this->titles->get('all')->count(),
            'data'         => $this->titles->get('all')->only(['id'])->toArray(),
        ]);
    }
}
