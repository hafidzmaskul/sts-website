<?php

use Illuminate\Support\Facades\Http;

test('it validates request parameters when sending email', function () {
    $response = $this->postJson('/api/send-email', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['sender', 'recipient', 'subject', 'content']);
});

test('it successfully sends email when MS Graph API returns success', function () {
    Http::fake([
        'https://login.microsoftonline.com/*' => Http::response([
            'access_token' => 'mocked-access-token',
        ], 200),
        'https://graph.microsoft.com/*' => Http::response([], 202),
    ]);

    $response = $this->postJson('/api/send-email', [
        'sender' => 'sender@example.com',
        'recipient' => 'recipient@example.com',
        'subject' => 'Test Subject',
        'content' => 'Test Content',
    ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Email sent successfully',
        ]);

    Http::assertSent(function ($request) {
        return $request->url() === 'https://login.microsoftonline.com/ae82217a-38f4-4a6c-bb15-3d21ef5988e5/oauth2/v2.0/token'
            && $request['client_id'] === 'b8833ad4-2c3e-4a07-a2f7-5146bea3cf00';
    });

    Http::assertSent(function ($request) {
        return $request->url() === 'https://graph.microsoft.com/v1.0/users/sender@example.com/sendMail'
            && $request->hasHeader('Authorization', 'Bearer mocked-access-token');
    });
});

test('it returns error response when MS Graph API fails', function () {
    Http::fake([
        'https://login.microsoftonline.com/*' => Http::response([
            'access_token' => 'mocked-access-token',
        ], 200),
        'https://graph.microsoft.com/*' => Http::response([
            'error' => [
                'message' => 'The sender mailbox is invalid.',
            ],
        ], 400),
    ]);

    $response = $this->postJson('/api/send-email', [
        'sender' => 'invalid-sender@example.com',
        'recipient' => 'recipient@example.com',
        'subject' => 'Test Subject',
        'content' => 'Test Content',
    ]);

    $response->assertStatus(400)
        ->assertJson([
            'success' => false,
            'error' => 'Failed to send email via Microsoft Graph API',
        ]);
});
