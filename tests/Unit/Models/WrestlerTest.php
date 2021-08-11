<?php

namespace Tests\Unit\Models;

use App\Casts\HeightCast;
use App\Enums\WrestlerStatus;
use App\Models\Contracts\Bookable;
use App\Models\Contracts\CanJoinStable;
use App\Models\SingleRosterMember;
use App\Models\User;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group wrestlers
 * @group roster
 * @group models
 */
class WrestlerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_wrestler_has_a_name()
    {
        $wrestler = new Wrestler(['name' => 'Example Wrestler Name']);

        $this->assertEquals('Example Wrestler Name', $wrestler->name);
    }

    /**
     * @test
     */
    public function a_wrestler_has_a_height()
    {
        $wrestler = new Wrestler(['height' => 70]);

        $this->assertEquals('70', $wrestler->height);
    }

    /**
     * @test
     */
    public function a_wrestler_has_a_weight()
    {
        $wrestler = new Wrestler(['weight' => 210]);

        $this->assertEquals(210, $wrestler->weight);
    }

    /**
     * @test
     */
    public function a_wrestler_has_a_hometown()
    {
        $wrestler = new Wrestler(['hometown' => 'Los Angeles, California']);

        $this->assertEquals('Los Angeles, California', $wrestler->hometown);
    }

    /**
     * @test
     */
    public function a_wrestler_can_have_a_signature_move()
    {
        $wrestler = new Wrestler(['signature_move' => 'Example Signature Move']);

        $this->assertEquals('Example Signature Move', $wrestler->signature_move);
    }

    /**
     * @test
     */
    public function a_wrestler_has_a_status()
    {
        $wrestler = new Wrestler();
        $wrestler->setRawAttributes(['status' => 'example'], true);

        $this->assertEquals('example', $wrestler->getRawOriginal('status'));
    }

    /**
     * @test
     */
    public function a_wrestler_status_gets_cast_as_a_wrestler_status_enum()
    {
        $wrestler = new Wrestler();

        $this->assertInstanceOf(WrestlerStatus::class, $wrestler->status);
    }

    /**
     * @test
     */
    public function a_wrestler_height_gets_cast_as_a_height_enum()
    {
        $wrestler = new Wrestler();

        $this->assertInstanceOf(HeightCast::class, $wrestler->height);
    }

    /**
     * @test
     */
    public function a_wrestler_is_a_single_roster_member()
    {
        $this->assertEquals(SingleRosterMember::class, get_parent_class(Wrestler::class));
    }

    /**
     * @test
     */
    public function a_wrestler_uses_has_a_unguarded_trait()
    {
        $this->assertUsesTrait('App\Models\Concerns\Unguarded', Wrestler::class);
    }

    /**
     * @test
     */
    public function a_wrestler_uses_soft_deleted_trait()
    {
        $this->assertUsesTrait('Illuminate\Database\Eloquent\SoftDeletes', Wrestler::class);
    }

    /**
     * @test
     */
    public function a_wrestler_uses_can_be_stable_member_trait()
    {
        $this->assertUsesTrait('App\Models\Concerns\CanJoinStable', Wrestler::class);
    }

    /**
     * @test
     */
    public function a_wrestler_implements_bookable_interface()
    {
        $this->assertContains(Bookable::class, class_implements(Wrestler::class));
    }

    /**
     * @test
     */
    public function a_wrestler_implements_can_join_stable_interface()
    {
        $this->assertContains(CanJoinStable::class, class_implements(Wrestler::class));
    }

    /**
     * @test
     */
    public function a_wrestler_can_be_associated_to_a_user()
    {
        $this->assertInstanceOf(User::class, (new Wrestler)->user);
    }
}
