<?php

use Illuminate\Support\Facades\Route;

test('custom 404 page renders when debug is disabled', function (): void {
    config()->set('app.debug', false);

    $response = $this->get('/missing-page');

    $response->assertNotFound();
    $response->assertSee('Page not found', false);
    $response->assertSee('Go home', false);
});

test('custom 500 page renders when debug is disabled', function (): void {
    config()->set('app.debug', false);

    Route::get('/trigger-500', fn (): never => abort(500));

    $response = $this->get('/trigger-500');

    $response->assertStatus(500);
    $response->assertSee('Something went wrong', false);
    $response->assertSee('Back', false);
});
