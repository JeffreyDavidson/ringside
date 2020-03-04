<?php

namespace Tests\Feature\SuperAdmin\Stables;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\StableFactory;
use Tests\TestCase;

/**
 * @group stables
 * @group superadmins
 * @group roster
 */
class ViewStablesListSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @var \Illuminate\Support\Collection */
    protected $stables;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $active = StableFactory::new()->active()->create();
        $pendingIntroduction = StableFactory::new()->pendingIntroduction()->create();
        $retired = StableFactory::new()->retired()->create();

        $this->stables = collect([
            'active'             => $active,
            'pending-introduction' => $pendingIntroduction,
            'retired'              => $retired,
            'all'                  => collect()
                                  ->concat($active)
                                  ->concat($pendingIntroduction)
                                  ->concat($retired),
        ]);
    }

    /** @test */
    public function a_super_administrator_can_view_stables_page()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $response = $this->indexRequest('stables');

        $response->assertOk();
        $response->assertViewIs('stables.index');
    }

    /** @test */
    public function a_super_administrator_can_view_all_stables()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $responseAjax = $this->ajaxJson(route('stables.index'));

        $responseAjax->assertJson([
            'recordsTotal' => $this->stables->get('all')->count(),
            'data'         => $this->stables->get('all')->only(['id'])->toArray(),
        ]);
    }

    /** @test */
    public function a_super_administrator_can_view_active_stables()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $responseAjax = $this->ajaxJson(route('stables.index', ['status' => 'active']));

        $responseAjax->assertJson([
            'recordsTotal' => $this->stables->get('active')->count(),
            'data'         => $this->stables->get('active')->only(['id'])->toArray(),
        ]);
    }

    /** @test */
    public function a_super_administrator_can_view_pending_introduction_stables()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $responseAjax = $this->ajaxJson(route('stables.index', ['status' => 'pending-introduction']));

        $responseAjax->assertJson([
            'recordsTotal' => $this->stables->get('pending-introduction')->count(),
            'data'         => $this->stables->get('pending-introduction')->only(['id'])->toArray(),
        ]);
    }

    /** @test */
    public function a_super_administrator_can_view_retired_stables()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $responseAjax = $this->ajaxJson(route('stables.index', ['status' => 'retired']));

        $responseAjax->assertJson([
            'recordsTotal' => $this->stables->get('retired')->count(),
            'data'         => $this->stables->get('retired')->only(['id'])->toArray(),
        ]);
    }
}
