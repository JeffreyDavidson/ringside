<?php

namespace Tests\Feature\Generic\Titles;

use Tests\TestCase;
use App\Models\Title;
use App\Exceptions\CannotBeIntroducedException;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

        $this->actAs('administrator');
        $title = factory(Title::class)->states('competable')->create();

        $response = $this->put(route('titles.introduce', $title));

        $response->assertForbidden();
    }
}
