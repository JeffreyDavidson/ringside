<?php

namespace Tests\Feature\Generic\Referees;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RefereeFactory;
use Tests\TestCase;

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
        $this->actAs(Role::ADMINISTRATOR);
        $referee = RefereeFactory::new()->softDeleted()->create();

        $response = $this->deleteRequest($referee);

        $response->assertNotFound();
    }
}
