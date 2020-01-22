<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group wrestlers
 * @group roster
 */
class WrestlerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Event::fake();
    }

    /** @test */
    public function a_wrestler_has_a_name()
    {
        $wrestler = factory(Wrestler::class)->create(['name' => 'Example Wrestler Name']);

        $this->assertEquals('Example Wrestler Name', $wrestler->name);
    }

    /** @test */
    public function a_wrestler_has_a_height()
    {
        $wrestler = factory(Wrestler::class)->create(['height' => 70]);

        $this->assertEquals('70', $wrestler->height);
    }

    /** @test */
    public function a_wrestler_has_a_weight()
    {
        $wrestler = factory(Wrestler::class)->create(['weight' => 210]);

        $this->assertEquals(210, $wrestler->weight);
    }

    /** @test */
    public function a_wrestler_has_a_hometown()
    {
        $wrestler = factory(Wrestler::class)->create(['hometown' => 'Los Angeles, California']);

        $this->assertEquals('Los Angeles, California', $wrestler->hometown);
    }

    /** @test */
    public function a_wrestler_can_have_a_signature_move()
    {
        $wrestler = factory(Wrestler::class)->create(['signature_move' => 'Example Signature Move']);

        $this->assertEquals('Example Signature Move', $wrestler->signature_move);
    }

    /** @test */
    public function a_wrestler_has_a_status()
    {
        $wrestler = factory(Wrestler::class)->create(['status' => 'Example Status']);

        $this->assertEquals('Example Status', $wrestler->getOriginal('status'));
    }
}
