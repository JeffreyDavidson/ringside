<?php

namespace Tests\Feature\Generic\Managers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\ManagerFactory;
use Tests\TestCase;

/**
 * @group managers
 * @group generics
 * @group roster
 */
class DeleteManagerFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_already_deleted_manager_cannot_be_deleted()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->softDeleted()->create();

        $response = $this->deleteRequest($manager);

        $response->assertNotFound();
    }
}
