<?php

namespace Tests\Unit\Models;

use App\Models\Wrestler;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group roster
 */
class WrestlerTest extends TestCase
{
    /** @test */
    public function a_wrestler_has_a_name()
    {
        $wrestler = new Wrestler(['name' => 'Example Wrestler Name']);

        $this->assertEquals('Example Wrestler Name', $wrestler->name);
    }

    /** @test */
    public function a_wrestler_has_a_height()
    {
        $wrestler = new Wrestler(['height' => 70]);

        $this->assertEquals('70', $wrestler->height);
    }

    /** @test */
    public function a_wrestler_has_a_weight()
    {
        $wrestler = new Wrestler(['weight' => 210]);

        $this->assertEquals(210, $wrestler->weight);
    }

    /** @test */
    public function a_wrestler_has_a_hometown()
    {
        $wrestler = new Wrestler(['hometown' => 'Los Angeles, California']);

        $this->assertEquals('Los Angeles, California', $wrestler->hometown);
    }

    /** @test */
    public function a_wrestler_can_have_a_signature_move()
    {
        $wrestler = new Wrestler(['signature_move' => 'Example Signature Move']);

        $this->assertEquals('Example Signature Move', $wrestler->signature_move);
    }

    /** @test */
    public function a_wrestler_has_a_status()
    {
        $wrestler = new Wrestler();
        $wrestler->setRawAttributes(['status' => 'example'], true);

        $this->assertEquals('example', $wrestler->getRawOriginal('status'));
    }
}
