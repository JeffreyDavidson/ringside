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

    /** @test */
    public function a_basic_user_can_view_their_wrestler_profile()
    {
        $signedInUser = $this->actAs(Role::BASIC);
        $wrestler = WrestlerFactory::new()->create(['user_id' => $signedInUser->id]);

        $response = $this->showRequest($wrestler);

        $response->assertOk();
    }

    /** @test */
    public function a_wrestlers_data_can_be_seen_on_their_profile()
    {
        $this->actAs(Role::ADMINISTRATOR);

        $wrestler = WrestlerFactory::new()->create([
            'name' => 'Wrestler 1',
            'height' => 78,
            'weight' => 220,
            'hometown' => 'Laraville, FL',
            'signature_move' => 'The Finisher',
        ]);

        $response = $this->showRequest($wrestler);

        $response->assertSee('Wrestler 1');
        $response->assertSee(e('6\'6"'));
        $response->assertSee('220 lbs');
        $response->assertSee('Laraville, FL');
        $response->assertSee('The Finisher');
    }
}
