<?php

namespace Tests\Feature\Generic\Titles;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Factories\TitleFactory;

/**
 * @group titles
 * @group generics
 */
class RestoreTitleFailureConditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_non_deleted_title_cannot_be_restored()
    {
        $this->actAs(Role::ADMINISTRATOR);
        $title = TitleFactory::new()->create();

        $response = $this->restoreRequest($title);

        $response->assertNotFound();
    }
}
