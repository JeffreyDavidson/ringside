<?php

namespace Tests\Feature\Http\Controllers\Wrestlers;

use App\Enums\Role;
use App\Http\Controllers\Wrestlers\RestoreController;
use App\Http\Controllers\Wrestlers\WrestlersController;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group feature-wrestlers
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
    public function invoke_restores_a_deleted_wrestler_and_redirects($administrators)
    {
        $wrestler = Wrestler::factory()->softDeleted()->create();

        $this
            ->actAs($administrators)
            ->patch(action([RestoreController::class], $wrestler))
            ->assertRedirect(action([WrestlersController::class, 'index']));

        $this->assertNull($wrestler->fresh()->deleted_at);
    }

    /**
     * @test
     */
    public function a_basic_user_cannot_restore_a_wrestler()
    {
        $wrestler = Wrestler::factory()->softDeleted()->create();

        $this
            ->actAs(Role::BASIC)
            ->patch(action([RestoreController::class], $wrestler))
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function a_guest_cannot_restore_a_wrestler()
    {
        $wrestler = Wrestler::factory()->softDeleted()->create();

        $this
            ->patch(action([RestoreController::class], $wrestler))
            ->assertRedirect(route('login'));
    }
}
