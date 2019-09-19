<?php

namespace Tests\Feature\Generic\Wrestlers;

use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group wrestlers
 * @group generics
 * @group roster
 */
class DeleteWrestlerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_already_deleted_wrestler_cannot_be_deleted()
    {
        $this->actAs('administrator');
        $wrestler = factory(Wrestler::class)->create();
        $wrestler->delete();

        $response = $this->deleteRequest($wrestler);

        $response->assertNotFound();
    }
}
