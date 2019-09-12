<?php

namespace Tests\Feature\Admin\Wrestlers;

use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group wrestlers
 * @group admins
 */
class EmployWrestlerSuccessCondtionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_employ_a_pending_employment_wrestler()
    {
        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->states('pending-employment')->create();

        $response = $this->put(route('wrestlers.employ', $wrestler));

        $response->assertRedirect(route('wrestlers.index'));
        tap($wrestler->fresh(), function (Wrestler $wrestler) {
            $this->assertTrue($wrestler->is_employed);
        });
    }
}
