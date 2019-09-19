<?php

namespace Tests\Feature\Generic\Referees;

use App\Models\Referee;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group referees
 * @group generics
 * @group roster
 */
class ViewRefereeBioPageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_referees_data_can_be_seen_on_their_profile()
    {
        $this->actAs('administrator');

        $referee = factory(Referee::class)->states('bookable')->create([
            'first_name' => 'John',
            'last_name' => 'Smith',
        ]);

        $response = $this->showRequest($referee);

        $response->assertSee('John Smith');
    }
}
