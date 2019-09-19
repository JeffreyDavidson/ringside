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
class DeleteRefereeFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_already_deleted_wrestler_cannot_be_deleted()
    {
        $this->actAs('administrator');
        $referee = factory(Referee::class)->create();
        $referee->delete();

        $response = $this->deleteRequest($referee);

        $response->assertNotFound();
    }
}
