<?php

namespace Tests\Feature\Admin\Referees;

use App\Models\Referee;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group referees
 * @group admins
 * @group roster
 */
class ViewRefereeBioPageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_view_a_referee_profile()
    {
        $this->actAs('administrator');
        $referee = factory(Referee::class)->create();

        $response = $this->showRequest($referee);

        $response->assertViewIs('referees.show');
        $this->assertTrue($response->data('referee')->is($referee));
    }
}
