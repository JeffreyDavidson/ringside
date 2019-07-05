<?php

namespace Tests\Feature\Wrestlers;

use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewWrestlersListTest extends TestCase
{
    use RefreshDatabase;

    /** @var \Illuminate\Support\Collection */
    protected $wrestlers;

    protected function setUp(): void
    {
        parent::setUp();
        $mapToIdAndName = function (Wrestler $wrestler) {
            return ['id' => $wrestler->id, 'name' => e($wrestler->name)];
        };

        $bookable  = factory(Wrestler::class, 3)->states('bookable')->create()->map($mapToIdAndName);
        $inactive  = factory(Wrestler::class, 3)->states('inactive')->create()->map($mapToIdAndName);
        $retired   = factory(Wrestler::class, 3)->states('retired')->create()->map($mapToIdAndName);
        $suspended = factory(Wrestler::class, 3)->states('suspended')->create()->map($mapToIdAndName);
        $injured   = factory(Wrestler::class, 3)->states('injured')->create()->map($mapToIdAndName);

        $this->wrestlers = collect([
            'bookable'  => $bookable,
            'inactive'  => $inactive,
            'retired'   => $retired,
            'suspended' => $suspended,
            'injured'   => $injured,
            'all'       => collect()
                ->concat($bookable)
                ->concat($inactive)
                ->concat($retired)
                ->concat($suspended)
                ->concat($injured)
        ]);
    }

    /** @test */
    public function a_basic_user_cannot_view_all_active_wrestlers()
    {
        $this->actAs('basic-user');

        $response = $this->get(route('wrestlers.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function a_guest_cannot_view_wrestlers_page()
    {
        $response = $this->get(route('wrestlers.index'));

        $response->assertRedirect(route('login'));
    }

    /**
     * @test
     * @dataProvider roleProvider
     */
    public function an_administrator_can_view_wrestlers_page($roleProvider)
    {
        $this->actAs($roleProvider);

        $response = $this->get(route('wrestlers.index'));

        $response->assertOk();
        $response->assertViewIs('wrestlers.index');
    }

    /**
     * @test
     * @dataProvider roleProvider
     */
    public function an_administrator_can_view_all_wrestlers($roleProvider)
    {
        $this->actAs($roleProvider);

        $responseAjax = $this->ajaxJson(route('wrestlers.index'));

        $responseAjax->assertJson([
            'recordsTotal' => $this->wrestlers->get('all')->count(),
            'data'         => $this->wrestlers->get('all')->toArray(),
        ]);
    }

    /**
     * @test
     * @dataProvider roleProvider
     */
    public function an_administrator_can_view_bookable_wrestlers($roleProvider)
    {
        $this->actAs($roleProvider);

        $responseAjax = $this->ajaxJson(route('wrestlers.index', ['status' => 'only_bookable']));

        $responseAjax->assertJson([
            'recordsTotal' => $this->wrestlers->get('bookable')->count(),
            'data'         => $this->wrestlers->get('bookable')->toArray(),
        ]);
    }

    /**
     * @test
     * @dataProvider roleProvider
     */
    public function an_administrator_can_view_inactive_wrestlers($roleProvider)
    {
        $this->actAs($roleProvider);

        $responseAjax = $this->ajaxJson(route('wrestlers.index', ['status' => 'only_inactive']));

        $responseAjax->assertJson([
            'recordsTotal' => $this->wrestlers->get('inactive')->count(),
            'data'         => $this->wrestlers->get('inactive')->toArray(),
        ]);
    }

    /**
     * @test
     * @dataProvider roleProvider
     */
    public function an_administrator_can_view_retired_wrestlers($roleProvider)
    {
        $this->actAs($roleProvider);

        $responseAjax = $this->ajaxJson(route('wrestlers.index', ['status' => 'only_retired']));

        $responseAjax->assertJson([
            'recordsTotal' => $this->wrestlers->get('retired')->count(),
            'data'         => $this->wrestlers->get('retired')->toArray(),
        ]);
    }

    /**
     * @test
     * @dataProvider roleProvider
     */
    public function an_administrator_can_view_suspended_wrestlers($roleProvider)
    {
        $this->actAs($roleProvider);

        $responseAjax = $this->ajaxJson(route('wrestlers.index', ['status' => 'only_suspended']));

        $responseAjax->assertJson([
            'recordsTotal' => $this->wrestlers->get('suspended')->count(),
            'data'         => $this->wrestlers->get('suspended')->toArray(),
        ]);
    }

    /**
     * @test
     * @dataProvider roleProvider
     */
    public function an_administrator_can_view_injured_wrestlers($roleProvider)
    {
        $this->actAs($roleProvider);

        $responseAjax = $this->ajaxJson(route('wrestlers.index', ['status' => 'only_injured']));

        $responseAjax->assertJson([
            'recordsTotal' => $this->wrestlers->get('injured')->count(),
            'data'         => $this->wrestlers->get('injured')->toArray(),
        ]);
    }

    /**
     * Provide a state of a user role to the test/
     *
     * @return array
     */
    public function roleProvider()
    {
        return [
            ['super-administrator'],
            ['administrator'],
        ];
    }
}
