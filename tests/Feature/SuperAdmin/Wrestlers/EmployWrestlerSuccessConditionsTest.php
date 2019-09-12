<?php

namespace Tests\Feature\SuperAdmin\Wrestlers;

use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group wrestlers
 * @group superadmins
 */
class EmployWrestlerSuccessCondtionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_super_administrator_can_employ_a_pending_employment_wrestler()
    {
        $this->actAs('super-administrator');
        $wrestler = factory(Wrestler::class)->states('pending-employment')->create();

        $response = $this->put(route('wrestlers.employ', $wrestler));

        $response->assertRedirect(route('wrestlers.index'));
        tap($wrestler->fresh(), function (Wrestler $wrestler) {
            $this->assertTrue($wrestler->is_bookable);
        });
    }
}
