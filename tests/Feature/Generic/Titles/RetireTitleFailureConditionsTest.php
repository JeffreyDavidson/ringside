<?php

namespace Tests\Feature\Generic\Titles;

use App\Enums\Role;
use App\Exceptions\CannotBeRetiredException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\TitleFactory;

/**
 * @group titles
 * @group generics
 */
class RetireTitleFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_retired_title_cannot_be_retired_again()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeRetiredException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->retired()->create();

        $response = $this->retireRequest($title);

        $response->assertForbidden();
    }

    /** @test */
    public function a_pending_introduction_title_cannot_be_retired()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeRetiredException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->pendingIntroduction()->create();

        $response = $this->retireRequest($title);

        $response->assertForbidden();
    }
}
