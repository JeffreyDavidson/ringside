<?php

namespace Tests\Feature\SuperAdmin\Wrestlers;

use App\Enums\Role;
use Tests\TestCase;
use App\Enums\WrestlerStatus;
use Tests\Factories\WrestlerFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group wrestlers
 * @group superadmins
 * @group roster
 */
class ViewWrestlersListSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @var \Illuminate\Support\Collection */
    protected $wrestlers;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $bookable = WrestlerFactory::new()->count(3)->bookable()->create();
        $pendingEmployment = WrestlerFactory::new()->count(3)->pendingEmployment()->create();
        $retired = WrestlerFactory::new()->count(3)->retired()->create();
        $suspended = WrestlerFactory::new()->count(3)->suspended()->create();
        $injured = WrestlerFactory::new()->count(3)->injured()->create();

        $this->wrestlers = collect([
            'bookable'             => $bookable,
            'pending-employment'   => $pendingEmployment,
            'retired'              => $retired,
            'suspended'            => $suspended,
            'injured'              => $injured,
            'all'                  => collect()
                                ->concat($bookable)
                                ->concat($pendingEmployment)
                                ->concat($retired)
                                ->concat($suspended)
                                ->concat($injured),
        ]);
    }

    /** @test */
    public function a_super_administrator_can_view_wrestlers_page()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $response = $this->indexRequest('wrestlers');
        dd($response);

        $response->assertOk();
        $response->assertViewIs('wrestlers.index');
    }

    /** @test */
    public function a_super_administrator_can_view_all_wrestlers()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $responseAjax = $this->ajaxJson(route('wrestlers.index'));

        $responseAjax->assertJson([
            'recordsTotal' => $this->wrestlers->get('all')->count(),
            'data'         => $this->wrestlers->get('all')->only(['id'])->toArray(),
        ]);
    }

    /** @test */
    public function a_super_administrator_can_view_bookable_wrestlers()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $responseAjax = $this->ajaxJson(route('wrestlers.index', ['status' => WrestlerStatus::BOOKABLE()->getValue()]));

        $responseAjax->assertJson([
            'recordsTotal' => $this->wrestlers->get('bookable')->count(),
            'data'         => $this->wrestlers->get('bookable')->only(['id'])->toArray(),
        ]);
    }

    /** @test */
    public function a_super_administrator_can_view_pending_employment_wrestlers()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $responseAjax = $this->ajaxJson(route('wrestlers.index', ['status' => WrestlerStatus::PENDING_EMPLOYMENT()->getValue()]));

        $responseAjax->assertJson([
            'recordsTotal' => $this->wrestlers->get('pending-employment')->count(),
            'data'         => $this->wrestlers->get('pending-employment')->only(['id'])->toArray(),
        ]);
    }

    /** @test */
    public function a_super_administrator_can_view_retired_wrestlers()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $responseAjax = $this->ajaxJson(route('wrestlers.index', ['status' => WrestlerStatus::RETIRED()->getValue()]));

        $responseAjax->assertJson([
            'recordsTotal' => $this->wrestlers->get('retired')->count(),
            'data'         => $this->wrestlers->get('retired')->only(['id'])->toArray(),
        ]);
    }

    /** @test */
    public function a_super_administrator_can_view_suspended_wrestlers()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $responseAjax = $this->ajaxJson(route('wrestlers.index', ['status' => WrestlerStatus::SUSPENDED()->getValue()]));

        $responseAjax->assertJson([
            'recordsTotal' => $this->wrestlers->get('suspended')->count(),
            'data'         => $this->wrestlers->get('suspended')->only(['id'])->toArray(),
        ]);
    }

    /** @test */
    public function a_super_administrator_can_view_injured_wrestlers()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);

        $responseAjax = $this->ajaxJson(route('wrestlers.index', ['status' => WrestlerStatus::INJURED()->getValue()]));

        $responseAjax->assertJson([
            'recordsTotal' => $this->wrestlers->get('injured')->count(),
            'data'         => $this->wrestlers->get('injured')->only(['id'])->toArray(),
        ]);
    }
}
