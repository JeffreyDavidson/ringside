<?php

namespace Tests\Unit\Http\Requests\Managers;

use App\Http\Requests\Managers\UpdateRequest;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/*
 * @group managers
 * @group roster
 */
class UpdateRequestTest extends TestCase
{
    use AdditionalAssertions;

    /** @var UpdateRequest */
    private $subject;

    public function setUp(): void
    {
        $this->subject = new UpdateRequest();
    }

    /** @test */
    public function rules_returns_validation_requirements()
    {
        $this->assertValidationRules([
            'first_name' => ['required', 'string', 'min:3'],
            'last_name' => ['required', 'string', 'min:3'],
            'started_at' => ['nullable', 'string', 'date_format:Y-m-d H:i:s'],
        ], $this->subject->rules());
    }
}
