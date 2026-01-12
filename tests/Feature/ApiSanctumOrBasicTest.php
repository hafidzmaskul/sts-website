<?php

use function Pest\Laravel\getJson;

it('returns json 401 for quote builder when unauthenticated', function () {
    $response = getJson('/api/quote-builder');

    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});
