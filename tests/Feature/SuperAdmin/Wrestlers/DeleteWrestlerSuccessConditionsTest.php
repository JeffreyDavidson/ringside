<?php

namespace Tests\Feature\SuperAdmin\Wrestlers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\WrestlerFactory;

/**
 * @group wrestlers
 * @group suoeradmins
 * @group roster
 */
class DeleteWrestlerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_delete_a_bookable_wrestler()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->bookable()->create();

        $response = $this->deleteRequest($wrestler);

        $response->assertRedirect(route('wrestlers.index'));
        $this->assertSoftDeleted('wrestlers', ['name' => $wrestler->name]);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_pending_employment_wrestler()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->pendingEmployment()->create();

        $response = $this->deleteRequest($wrestler);

        $response->assertRedirect(route('wrestlers.index'));
        $this->assertSoftDeleted('wrestlers', ['name' => $wrestler->name]);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_retired_wrestler()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->retired()->create();

        $response = $this->deleteRequest($wrestler);

        $response->assertRedirect(route('wrestlers.index'));
        $this->assertSoftDeleted('wrestlers', ['name' => $wrestler->name]);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_suspended_wrestler()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->suspended()->create();

        $response = $this->deleteRequest($wrestler);

        $response->assertRedirect(route('wrestlers.index'));
        $this->assertSoftDeleted('wrestlers', ['name' => $wrestler->name]);
    }

    /** @test */
    public function a_super_administrator_can_delete_a_injured_wrestler()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->injured()->create();

        $response = $this->deleteRequest($wrestler);

        $response->assertRedirect(route('wrestlers.index'));
        $this->assertSoftDeleted('wrestlers', ['name' => $wrestler->name]);
    }
}
