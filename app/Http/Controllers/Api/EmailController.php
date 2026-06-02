<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EmailController extends Controller
{
    /**
     * Send email using Microsoft Graph API with OAuth.
     */
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sender' => 'required|email',
            'recipient' => 'required|email',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $tenantId = config('services.microsoft.tenant_id');
        $clientId = config('services.microsoft.client_id');
        $clientSecret = config('services.microsoft.client_secret');

        // 1. Get access token from Microsoft Identity Platform
        $tokenUrl = "https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token";

        $tokenResponse = Http::asForm()->post($tokenUrl, [
            'client_id' => $clientId,
            'scope' => 'https://graph.microsoft.com/.default',
            'client_secret' => $clientSecret,
            'grant_type' => 'client_credentials',
        ]);

        if ($tokenResponse->failed()) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to obtain access token',
                'details' => $tokenResponse->json(),
            ], 400);
        }

        $accessToken = $tokenResponse->json()['access_token'];

        // 2. Send email via Microsoft Graph API sendMail endpoint
        $sendMailUrl = "https://graph.microsoft.com/v1.0/users/{$validated['sender']}/sendMail";

        $emailBody = [
            'message' => [
                'subject' => $validated['subject'],
                'body' => [
                    'contentType' => 'Text',
                    'content' => $validated['content'],
                ],
                'toRecipients' => [
                    [
                        'emailAddress' => [
                            'address' => $validated['recipient'],
                        ],
                    ],
                ],
            ],
            'saveToSentItems' => 'false',
        ];

        $sendMailResponse = Http::withToken($accessToken)
            ->post($sendMailUrl, $emailBody);

        if ($sendMailResponse->failed()) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to send email via Microsoft Graph API',
                'details' => $sendMailResponse->json(),
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Email sent successfully',
        ]);
    }
}
