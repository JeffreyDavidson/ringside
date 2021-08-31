<?php

namespace Tests\Feature\Http\Controllers\Stables;

use App\Enums\Role;
use App\Enums\StableStatus;
use App\Http\Controllers\Stables\RestoreController;
use App\Http\Controllers\Stables\StablesController;
use App\Models\Stable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group stables
 * @group feature-stables
 * @group roster
 * @group feature-roster
 */
class RestoreControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider administrators
     */
    public function invoke_restores_a_stable_and_redirects($administrators)
    {
        $stable = Stable::factory()->softDeleted()->create();

        $this
            ->actAs($administrators)
            ->patch(action([RestoreController::class], $stable))
            ->assertRedirect(action([StablesController::class, 'index']));

        tap($stable->fresh(), function ($stable) {
            $this->assertEquals(StableStatus::UNACTIVATED, $stable->status);
            $this->assertNull($stable->fresh()->deleted_at);
        });
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_restore_a_stable()
    {
        $stable = Stable::factory()->softDeleted()->create();

        $this
            ->actAs(Role::BASIC)
            ->patch(action([RestoreController::class], $stable))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_restore_a_stable()
    {
        $stable = Stable::factory()->softDeleted()->create();

        $this->patch(action([RestoreController::class], $stable))
            ->assertRedirect(route('login'));
    }
}
