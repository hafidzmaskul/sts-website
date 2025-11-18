<?php

declare(strict_types=1);

use function Pest\Laravel\postJson;

it('accepts and validates contact submissions', function () {
    $response = postJson(route('api.contact.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'subject' => 'Test Subject',
        'message' => 'This is a test message.',
    ]);

    $response->assertCreated();
});
