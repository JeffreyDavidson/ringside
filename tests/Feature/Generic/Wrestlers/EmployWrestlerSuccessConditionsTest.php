<?php

namespace Tests\Feature\Generic\Wrestler;

use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group wrestlers
 * @group generics
 * @group roster
 */
class EmployWrestlerSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_wrestler_without_a_current_employment_can_be_employed()
    {
        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->create();

        $response = $this->employRequest($wrestler);

        $response->assertRedirect(route('wrestlers.index'));
        tap($wrestler->fresh(), function ($wrestler) {
            $this->assertTrue($wrestler->employment()->exists());
        });
    }
}
