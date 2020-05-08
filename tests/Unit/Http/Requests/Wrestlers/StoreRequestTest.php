<?php

namespace Tests\Unit\Http\Requests\Venues;

use App\Http\Requests\Venues\StoreRequest;
use Tests\TestCase;

class StoreRequestTest extends TestCase
{
    /** @var StoreRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new StoreRequest();
    }

    /** @test */
    public function all_validation_rules_match()
    {
        $this->assertEquals(
            [
                'name' => [
                    'required',
                    'string'
                ],
                'address1' => [
                    'required',
                    'string'
                ],
                'address2' => [
                    'nullable',
                    'string'
                ],
                'city' => [
                    'required',
                    'string'
                ],
                'state' => [
                    'required',
                    'string'
                ],
                'zip' => [
                    'required',
                    'integer',
                    'digits:5'
                ],
            ],
            $this->subject->rules()
        );
    }

    /** @test */
    public function authorized_users_can_save_a_venue()
    {
        $this->assertTrue($this->subject->authorize());
    }
}
