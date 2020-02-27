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
class ViewManagerBioPageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_managers_data_can_be_seen_on_their_profile()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $manager = ManagerFactory::new()->create(['first_name' => 'John', 'last_name' => 'Smith']);

        $response = $this->showRequest($manager);

        $response->assertSee('John Smith');
    }
}
