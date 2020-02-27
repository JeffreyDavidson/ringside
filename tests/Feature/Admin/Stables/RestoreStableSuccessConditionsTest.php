<?php

namespace Tests\Feature\Admin\Stables;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\StableFactory;
use Tests\TestCase;

/**
 * @group stables
 * @group admins
 * @group roster
 */
class RestoreStableSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_restore_a_deleted_stable()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $stable = StableFactory::new()->softDeleted()->create();

        $response = $this->restoreRequest($stable);

        $response->assertRedirect(route('stables.index'));
        $this->assertNull($stable->fresh()->deleted_at);
    }
}
