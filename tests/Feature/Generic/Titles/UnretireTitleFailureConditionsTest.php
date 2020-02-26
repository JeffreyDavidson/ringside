<?php

namespace Tests\Feature\Generic\Titles;

use App\Enums\Role;
use App\Exceptions\CannotBeUnretiredException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use TitleFactory;

/**
 * @group titles
 * @group generics
 */
class UnretireTitleFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_competable_title_cannot_unretire()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeUnretiredException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->competable()->create();

        $response = $this->unretireRequest($title);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_introduction_title_cannot_unretire()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeUnretiredException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->pendingIntroduction()->create();

        $response = $this->unretireRequest($title);

        $response->assertForbidden();
    }
}
