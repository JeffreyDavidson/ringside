<?php

namespace Tests\Unit\Http\Requests\Managers;

use App\Http\Requests\Managers\UpdateFormRequest;
use JMac\Testing\Traits\HttpTestAssertions;
use Tests\TestCase;

/*
 * @group managers
 * @group roster
 */
class UpdateFormRequestTest extends TestCase
{
    use HttpTestAssertions;

    /** @var UpdateFormRequest */
    private $subject;

    public function setUp(): void
    {
        $this->subject = new UpdateFormRequest();
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
