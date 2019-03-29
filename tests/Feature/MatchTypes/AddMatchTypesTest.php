<?php

namespace Tests\Feature\MatchTypes;

use Tests\TestCase;
use App\Models\MatchType;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddMatchTypesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        Artisan::call('db:seed', ['--class' => 'MatchTypesTableSeeder ', '--database' => 'testing']);
    }

    /** @test */
    public function a_singles_match_type_is_saved_in_database()
    {
        $matchtypes = MatchType::all();

        $this->assertContains('name', 'Singles');
    }
}
