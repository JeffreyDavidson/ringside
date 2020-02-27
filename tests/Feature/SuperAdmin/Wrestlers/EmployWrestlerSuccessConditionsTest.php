<?php

namespace Tests\Feature\SuperAdmin\Wrestlers;

use App\Enums\Role;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\WrestlerFactory;

/**
 * @group wrestlers
 * @group superadmins
 * @group roster
 */
class EmployWrestlerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_employ_a_pending_employment_wrestler()
    {
        $this->actAs(Role::SUPER_ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->pendingEmployment()->create();

        $response = $this->employRequest($wrestler);

        $response->assertRedirect(route('wrestlers.index'));
        tap($wrestler->fresh(), function (Wrestler $wrestler) {
            $this->assertTrue($wrestler->isCurrentlyEmployed());
        });
    }
}
