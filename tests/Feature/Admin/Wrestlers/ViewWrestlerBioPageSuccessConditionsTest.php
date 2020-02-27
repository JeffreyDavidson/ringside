<?php

namespace Tests\Feature\Admin\Wrestlers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\WrestlerFactory;

/**
 * @group wrestlers
 * @group admins
 * @group roster
 */
class ViewWrestlerBioPageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_can_view_a_wrestler_profile()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $wrestler = WrestlerFactory::new()->create();

        $response = $this->showRequest($wrestler);

        $response->assertViewIs('wrestlers.show');
        $this->assertTrue($response->data('wrestler')->is($wrestler));
    }
}
