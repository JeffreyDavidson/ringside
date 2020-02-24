<?php

namespace Tests\Feature\Generic\Titles;

use App\Enums\Role;
use App\Exceptions\CannotBeIntroducedException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use TitleFactory;

/**
 * @group titles
 * @group generics
 */
class IntroduceTitleFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_competable_title_cannot_be_introduced()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CannotBeIntroducedException::class);

        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->competable()->create();

        $response = $this->introduceRequest($title);

        $response->assertForbidden();
    }
}
