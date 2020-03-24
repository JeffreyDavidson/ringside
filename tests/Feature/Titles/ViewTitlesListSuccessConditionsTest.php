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

    protected function setUp(): void
    {
        parent::setUp();

        $competable = TitleFactory::new()->count(3)->competable()->create();
        $pendingIntroduction = TitleFactory::new()->count(3)->pendingIntroduction()->create();
        $retired = TitleFactory::new()->count(3)->retired()->create();

        $this->titles = collect([
            'competable'           => $competable,
            'pending-introduction' => $pendingIntroduction,
            'retired'              => $retired,
            'all'                  => collect()
                                ->concat($competable)
                                ->concat($pendingIntroduction)
                                ->concat($retired),
        ]);
    }

    /** @test */
    public function an_administrator_can_view_titles_page()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $response = $this->indexRequest('titles');

        $response->assertOk();
        $response->assertViewIs('titles.index');
    }

    /** @test */
    public function an_administrator_can_view_all_titles()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $responseAjax = $this->ajaxJson(route('titles.index'));

        $responseAjax->assertJson([
            'recordsTotal' => $this->titles->get('all')->count(),
            'data'         => $this->titles->get('all')->only(['id'])->toArray(),
        ]);
    }

    /** @test */
    public function an_administrator_can_view_all_competable_titles()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $responseAjax = $this->ajaxJson(route('titles.index', ['status' => 'competable']));

        $responseAjax->assertJson([
            'recordsTotal' => $this->titles->get('competable')->count(),
            'data'         => $this->titles->get('competable')->only(['id'])->toArray(),
        ]);
    }

    /** @test */
    public function an_administrator_can_view_all_pending_introduction_titles()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $responseAjax = $this->ajaxJson(route('titles.index', ['status' => 'pending-introduction']));

        $responseAjax->assertJson([
            'recordsTotal' => $this->titles->get('pending-introduction')->count(),
            'data'         => $this->titles->get('pending-introduction')->only(['id'])->toArray(),
        ]);
    }

    /** @test */
    public function an_administrator_can_view_all_retired_titles()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $responseAjax = $this->ajaxJson(route('titles.index', ['status' => 'retired']));

        $responseAjax->assertJson([
            'recordsTotal' => $this->titles->get('retired')->count(),
            'data'         => $this->titles->get('retired')->only(['id'])->toArray(),
        ]);
    }
}
