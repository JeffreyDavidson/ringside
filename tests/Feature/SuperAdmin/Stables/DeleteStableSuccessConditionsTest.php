<?php

namespace Tests\Feature\SuperAdmin\Stables;

use App\Enums\Role;
use App\Models\Stable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group stables
 * @group superadmins
 * @group roster
 */
class DeleteStableSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_delete_a_bookable_stable()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $stable = factory(Stable::class)->states('bookable')->create();

        $response = $this->deleteRequest($stable);

        $response->assertRedirect(route('stables.index'));
        $this->assertSoftDeleted('stables', ['name' => $stable->name]);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_pending_introduction_stable()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $stable = factory(Stable::class)->states('pending-introduction')->create();

        $response = $this->deleteRequest($stable);

        $response->assertRedirect(route('stables.index'));
        $this->assertSoftDeleted('stables', ['name' => $stable->name]);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_retired_stable()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $stable = factory(Stable::class)->states('retired')->create();

        $response = $this->deleteRequest($stable);

        $response->assertRedirect(route('stables.index'));
        $this->assertSoftDeleted('stables', ['name' => $stable->name]);
    }
}
