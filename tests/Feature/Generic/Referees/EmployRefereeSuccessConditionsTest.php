<?php

namespace Tests\Feature\Generic\Referees;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\RefereeFactory;
use Tests\TestCase;

/**
 * @group referees
 * @group generics
 * @group roster
 */
class EmployRefereeSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_referee_without_a_current_employment_can_be_employed()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->create();

        $response = $this->employRequest($referee);

        $response->assertRedirect(route('referees.index'));
        tap($referee->fresh(), function ($referee) {
            $this->assertTrue($referee->currentEmployment()->exists());
        });
    }
}
