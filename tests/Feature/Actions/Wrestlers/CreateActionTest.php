<?php

test('store creates a wrestler and redirects', function () {
    $data = StoreRequest::factory()->create([
        'name' => 'Example Wrestler Name',
        'feet' => 6,
        'inches' => 10,
        'weight' => 300,
        'hometown' => 'Laraville, New York',
        'signature_move' => null,
        'start_date' => null,
    ]);

    $this->actingAs(administrator())
        ->from(action([WrestlersController::class, 'create']))
        ->post(action([WrestlersController::class, 'store']), $data)
        ->assertValid()
        ->assertRedirect(action([WrestlersController::class, 'index']));

    expect(Wrestler::latest()->first())
        ->name->toBe('Example Wrestler Name')
        ->height->toBe(82)
        ->weight->toBe(300)
        ->hometown->toBe('Laraville, New York')
        ->signature_move->toBeNull()
        ->employments->toBeEmpty();
});

test('store creates a wrestler with a signature move and redirects', function () {
    $data = StoreRequest::factory()->create([
        'signature_move' => 'Example Finishing Move',
    ]);

    $this->actingAs(administrator())
        ->from(action([WrestlersController::class, 'create']))
        ->post(action([WrestlersController::class, 'store']), $data)
        ->assertValid()
        ->assertRedirect(action([WrestlersController::class, 'index']));

    expect(Wrestler::latest()->first())
        ->signature_move->toBe('Example Finishing Move');
});

test('an employment is created for the wrestler if start date is filled in request', function () {
    $dateTime = Carbon::now()->toDateTimeString();
    $data = StoreRequest::factory()->create([
        'start_date' => $dateTime,
    ]);

    $this->actingAs(administrator())
        ->from(action([WrestlersController::class, 'create']))
        ->post(action([WrestlersController::class, 'store']), $data)
        ->assertValid()
        ->assertRedirect(action([WrestlersController::class, 'index']));

    expect(Wrestler::latest()->first())
        ->employments->toHaveCount(1)
        ->employments->first()->started_at->toDateTimeString()->toBe($dateTime);
});

test('invoke throws exception for employing a non employable wrestler', function ($factoryState) {
    $this->withoutExceptionHandling();

    $wrestler = Wrestler::factory()->{$factoryState}()->create();

    $this->actingAs(administrator())
        ->patch(action([EmployController::class], $wrestler));
})->throws(CannotBeEmployedException::class)->with([
    'suspended',
    'injured',
    'bookable',
    'retired',
]);
