<?php

namespace Tests\Feature\Admin\Managers;

use Tests\TestCase;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group managers
 * @group admins
 * @group roster
 */
class ViewManagersListSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @var \Illuminate\Support\Collection */
    protected $managers;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $available            = factory(Manager::class, 3)->states('available')->create();
        $pendingEmployment   = factory(Manager::class, 3)->states('pending-employment')->create();
        $retired             = factory(Manager::class, 3)->states('retired')->create();
        $suspended           = factory(Manager::class, 3)->states('suspended')->create();
        $injured             = factory(Manager::class, 3)->states('injured')->create();

        $this->managers = collect([
            'available'             => $available,
            'pending-employment'   => $pendingEmployment,
            'retired'              => $retired,
            'suspended'            => $suspended,
            'injured'              => $injured,
            'all'                  => collect()
                                ->concat($available)
                                ->concat($pendingEmployment)
                                ->concat($retired)
                                ->concat($suspended)
                                ->concat($injured)
        ]);
    }

    /** @test */
    public function an_administrator_can_view_managers_page()
    {
        $this->actAs('administrator');

        $response = $this->get(route('managers.index'));

        $response->assertOk();
        $response->assertViewIs('managers.index');
    }

    /** @test */
    public function an_administrator_can_view_all_managers()
    {
        $this->actAs('administrator');

        $responseAjax = $this->ajaxJson(route('managers.index'));

        $responseAjax->assertJson([
            'recordsTotal' => $this->managers->get('all')->count(),
            'data'         => $this->managers->get('all')->only(['id'])->toArray(),
        ]);
    }

    /** @test */
    public function an_administrator_can_view_available_managers()
    {
        $this->actAs('administrator');

        $responseAjax = $this->ajaxJson(route('managers.index', ['status' => 'available']));

        $responseAjax->assertJson([
            'recordsTotal' => $this->managers->get('available')->count(),
            'data'         => $this->managers->get('available')->only(['id'])->toArray(),
        ]);
    }

    /** @test */
    public function an_administrator_can_view_pending_employment_managers()
    {
        $this->actAs('administrator');

        $responseAjax = $this->ajaxJson(route('managers.index', ['status' => 'pending-employment']));

        $responseAjax->assertJson([
            'recordsTotal' => $this->managers->get('pending-employment')->count(),
            'data'         => $this->managers->get('pending-employment')->only(['id'])->toArray(),
        ]);
    }

    /** @test */
    public function an_administrator_can_view_retired_managers()
    {
        $this->actAs('administrator');

        $responseAjax = $this->ajaxJson(route('managers.index', ['status' => 'retired']));

        $responseAjax->assertJson([
            'recordsTotal' => $this->managers->get('retired')->count(),
            'data'         => $this->managers->get('retired')->only(['id'])->toArray(),
        ]);
    }

    /** @test */
    public function an_administrator_can_view_suspended_managers()
    {
        $this->actAs('administrator');

        $responseAjax = $this->ajaxJson(route('managers.index', ['status' => 'suspended']));

        $responseAjax->assertJson([
            'recordsTotal' => $this->managers->get('suspended')->count(),
            'data'         => $this->managers->get('suspended')->only(['id'])->toArray(),
        ]);
    }

    /** @test */
    public function an_administrator_can_view_injured_managers()
    {
        $this->actAs('administrator');

        $responseAjax = $this->ajaxJson(route('managers.index', ['status' => 'injured']));

        $responseAjax->assertJson([
            'recordsTotal' => $this->managers->get('injured')->count(),
            'data'         => $this->managers->get('injured')->only(['id'])->toArray(),
        ]);
    }
}
