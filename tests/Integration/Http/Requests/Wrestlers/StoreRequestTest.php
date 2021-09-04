<?php

namespace Tests\Integration\Http\Requests\Wrestlers;

use App\Http\Requests\Wrestlers\StoreRequest;
use App\Models\User;
use App\Models\Wrestler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\Rule;
use Tests\TestCase;
use Tests\ValidatesRequests;

/**
 * @group wrestlers
 * @group roster
 * @group requests
 */
class StoreRequestTest extends TestCase
{
    use RefreshDatabase,
        ValidatesRequests;

    /**
     * @test
     */
    public function an_administrator_is_authorized_to_make_this_request()
    {
        $administrator = User::factory()->administrator()->create();

        $this->createRequest(StoreRequest::class)
            ->by($administrator)
            ->assertAuthorized();
    }

    /**
     * @test
     */
    public function a_non_administrator_is_not_authorized_to_make_this_request()
    {
        $user = User::factory()->create();

        $this->createRequest(StoreRequest::class)
            ->by($user)
            ->assertNotAuthorized();
    }

    /**
     * @test
     */
    public function wrestler_name_is_required()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'name' => null,
            ])
            ->assertFailsValidation(['name' => 'required']);
    }

    /**
     * @test
     */
    public function wrestler_name_must_be_a_string()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'name' => 123,
            ])
            ->assertFailsValidation(['name' => 'string']);
    }

    /**
     * @test
     */
    public function wrestler_name_must_be_at_least_3_characters()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'name' => 'ab',
            ])
            ->assertFailsValidation(['name' => 'min:3']);
    }

    /**
     * @test
     */
    public function wrestler_name_must_be_unique()
    {
        Wrestler::factory()->create(['name' => 'Example Wrestler Name']);

        $this->createRequest(StoreRequest::class)
            ->validate([
                'name' => 'Example Wrestler Name',
            ])
            ->assertFailsValidation(['name' => Rule::unique('wrestlers')]);
    }

    /**
     * @test
     */
    public function wrestler_height_in_feet_is_required()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'feet' => null,
            ])
            ->assertFailsValidation(['feet' => 'required']);
    }

    /**
     * @test
     */
    public function wrestler_height_for_feet_must_be_an_integer()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'feet' => 'not-an-integer',
            ])
            ->assertFailsValidation(['feet' => 'integer']);
    }

    /**
     * @test
     */
    public function wrestler_height_for_inches_is_required()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'inches' => null,
            ])
            ->assertFailsValidation(['inches' => 'required']);
    }

    /**
     * @test
     */
    public function wrestler_height_for_inches_must_be_an_integer()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'inches' => 'not-an-integer',
            ])
            ->assertFailsValidation(['inches' => 'integer']);
    }

    /**
     * @test
     */
    public function wrestler_height_for_inches_has_a_max_of_eleven()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'inches' => 12,
            ])
            ->assertFailsValidation(['inches' => 'max:11']);
    }

    /**
     * @test
     */
    public function wrestler_weight_is_required()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'weight' => null,
            ])
            ->assertFailsValidation(['weight' => 'required']);
    }

    /**
     * @test
     */
    public function wrestler_weight_must_be_an_integer()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'weight' => 'not-an-integer',
            ])
            ->assertFailsValidation(['weight' => 'integer']);
    }

    /**
     * @test
     */
    public function wrestler_hometown_is_required()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'hometown' => null,
            ])
            ->assertFailsValidation(['hometown' => 'required']);
    }

    /**
     * @test
     */
    public function wrestler_hometown_must_be_a_string()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'hometown' => 12345,
            ])
            ->assertFailsValidation(['hometown' => 'string']);
    }

    /**
     * @test
     */
    public function wrestler_signature_move_is_optional()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'signature_move' => null,
                'name' => 'Example Wrestler Name',
                'feet' => 6,
                'inches' => 4,
                'weight' => 240,
                'hometown' => 'Las Vegan, NV',
            ])
            ->assertPassesValidation();
    }

    /**
     * @test
     */
    public function wrestler_signature_move_must_be_a_string_if_provided()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'signature_move' => 12345,
            ])
            ->assertFailsValidation(['signature_move' => 'string']);
    }

    /**
     * @test
     */
    public function wrestler_started_at_is_optional()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'started_at' => null,
                'name' => 'Example Wrestler Name',
                'feet' => 6,
                'inches' => 4,
                'weight' => 240,
                'hometown' => 'Las Vegan, NV',
            ])
            ->assertPassesValidation();
    }

    /**
     * @test
     */
    public function wrestler_started_at_must_be_a_string_if_provided()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'started_at' => 12345,
            ])
            ->assertFailsValidation(['started_at' => 'string']);
    }

    /**
     * @test
     */
    public function wrestler_started_at_must_be_in_the_correct_date_format()
    {
        $this->createRequest(StoreRequest::class)
            ->validate([
                'started_at' => 'not-a-date',
            ])
            ->assertFailsValidation(['started_at' => 'date']);
    }
}
