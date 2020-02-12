<?php

namespace Tests\Feature\Generic\Wrestlers;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use WrestlerFactory;

/**
 * @group wrestlers
 * @group generics
 * @group roster
 */
class ViewWrestlerBioPageSuccessConditionsTest extends TestCase
{
    use RefreshDatabase;

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
