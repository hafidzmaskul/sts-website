<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    config(['mail.default' => 'microsoft-graph']);
    config(['services.microsoft' => [
        'tenant_id' => 'ae82217a-38f4-4a6c-bb15-3d21ef5988e5',
        'client_id' => 'b8833ad4-2c3e-4a07-a2f7-5146bea3cf00',
        'client_secret' => 'mocked-client-secret',
    ]]);
    Cache::forget('microsoft_graph_token');
});

test('it successfully sends raw email via microsoft-graph transport', function () {
    Http::fake([
        'https://login.microsoftonline.com/*' => Http::response([
            'access_token' => 'mocked-access-token',
        ], 200),
        'https://graph.microsoft.com/*' => Http::response([], 202),
    ]);

    Mail::raw('Test text body', function ($message) {
        $message->to('recipient@example.com')
            ->subject('Test Subject')
            ->from('support@gaia-ol.com');
    });

    Http::assertSent(function ($request) {
        return $request->url() === 'https://login.microsoftonline.com/ae82217a-38f4-4a6c-bb15-3d21ef5988e5/oauth2/v2.0/token'
            && $request['client_id'] === 'b8833ad4-2c3e-4a07-a2f7-5146bea3cf00';
    });

    Http::assertSent(function ($request) {
        return $request->url() === 'https://graph.microsoft.com/v1.0/users/support@gaia-ol.com/sendMail'
            && $request->hasHeader('Authorization', 'Bearer mocked-access-token')
            && $request['message']['subject'] === 'Test Subject'
            && $request['message']['body']['content'] === 'Test text body'
            && $request['message']['body']['contentType'] === 'Text'
            && $request['message']['toRecipients'][0]['emailAddress']['address'] === 'recipient@example.com';
    });
});

test('it caches access token and does not request new token on subsequent send', function () {
    Http::fake([
        'https://login.microsoftonline.com/*' => Http::response([
            'access_token' => 'mocked-access-token',
        ], 200),
        'https://graph.microsoft.com/*' => Http::response([], 202),
    ]);

    Mail::raw('Test body 1', function ($message) {
        $message->to('recipient1@example.com')
            ->subject('Subject 1')
            ->from('support@gaia-ol.com');
    });

    Mail::raw('Test body 2', function ($message) {
        $message->to('recipient2@example.com')
            ->subject('Subject 2')
            ->from('support@gaia-ol.com');
    });

    // Token endpoint should have been called only once, plus 2 sendMail calls = 3 requests total
    Http::assertSentCount(3);
});

test('it handles 401 Unauthorized by clearing cache and retrying', function () {
    Cache::put('microsoft_graph_token', 'expired-token', 3000);

    Http::fake([
        'https://login.microsoftonline.com/*' => Http::response([
            'access_token' => 'fresh-access-token',
        ], 200),
        'https://graph.microsoft.com/*' => Http::sequence()
            ->push(['error' => 'Unauthorized'], 401)
            ->push([], 202),
    ]);

    Mail::raw('Retry body', function ($message) {
        $message->to('recipient@example.com')
            ->subject('Retry Subject')
            ->from('support@gaia-ol.com');
    });

    // Check that cache was updated with fresh token
    expect(Cache::get('microsoft_graph_token'))->toBe('fresh-access-token');

    // Token endpoint should be called to get fresh token after 401
    Http::assertSent(function ($request) {
        return $request->url() === 'https://login.microsoftonline.com/ae82217a-38f4-4a6c-bb15-3d21ef5988e5/oauth2/v2.0/token';
    });

    // Verify first send attempted with expired token
    Http::assertSent(function ($request) {
        return $request->url() === 'https://graph.microsoft.com/v1.0/users/support@gaia-ol.com/sendMail'
            && $request->hasHeader('Authorization', 'Bearer expired-token');
    });

    // Verify second send attempted with fresh token
    Http::assertSent(function ($request) {
        return $request->url() === 'https://graph.microsoft.com/v1.0/users/support@gaia-ol.com/sendMail'
            && $request->hasHeader('Authorization', 'Bearer fresh-access-token');
    });
});

test('it handles email with HTML content and attachments', function () {
    Http::fake([
        'https://login.microsoftonline.com/*' => Http::response([
            'access_token' => 'mocked-access-token',
        ], 200),
        'https://graph.microsoft.com/*' => Http::response([], 202),
    ]);

    Mail::send([], [], function ($message) {
        $message->to('recipient@example.com')
            ->subject('HTML Subject')
            ->from('support@gaia-ol.com')
            ->html('<h1>Hello HTML</h1>')
            ->attachData('file content', 'test.txt', ['mime' => 'text/plain']);
    });

    Http::assertSent(function ($request) {
        if ($request->url() !== 'https://graph.microsoft.com/v1.0/users/support@gaia-ol.com/sendMail') {
            return false;
        }

        $message = $request['message'];
        $attachments = $message['attachments'];

        return $message['body']['contentType'] === 'HTML'
            && $message['body']['content'] === '<h1>Hello HTML</h1>'
            && count($attachments) === 1
            && $attachments[0]['name'] === 'test.txt'
            && $attachments[0]['contentType'] === 'text/plain'
            && $attachments[0]['contentBytes'] === base64_encode('file content');
    });
});

test('it throws exception on failure', function () {
    Http::fake([
        'https://login.microsoftonline.com/*' => Http::response([
            'access_token' => 'mocked-access-token',
        ], 200),
        'https://graph.microsoft.com/*' => Http::response([
            'error' => [
                'message' => 'Invalid request payload',
            ],
        ], 400),
    ]);

    expect(fn () => Mail::raw('Fail', function ($message) {
        $message->to('recipient@example.com')->from('support@gaia-ol.com');
    }))->toThrow(\Exception::class);
});
