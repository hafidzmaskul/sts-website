<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TestEmailController extends Controller
{
    /**
     * Send test email using Microsoft Graph API.
     */
    public function sendEmail(Request $request): JsonResponse
    {
        $tenantId = config('services.microsoft.tenant_id');
        $clientId = config('services.microsoft.client_id');
        $clientSecret = config('services.microsoft.client_secret');

        $senderEmail = $request->query('sender', 'sender@example.com');
        $recipientEmail = $request->query('recipient', 'recipient@example.com');

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
                'error' => 'Failed to obtain access token',
                'details' => $tokenResponse->json(),
            ], 400);
        }

        $accessToken = $tokenResponse->json()['access_token'];

        // 2. Send email via Microsoft Graph API sendMail endpoint
        $sendMailUrl = "https://graph.microsoft.com/v1.0/users/{$senderEmail}/sendMail";

        $emailBody = [
            'message' => [
                'subject' => 'Test Email via MS Graph API OAuth',
                'body' => [
                    'contentType' => 'Text',
                    'content' => 'Hello! This is a test email sent using Microsoft Graph API and OAuth 2.0 client credentials flow.',
                ],
                'toRecipients' => [
                    [
                        'emailAddress' => [
                            'address' => $recipientEmail,
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
                'error' => 'Failed to send email via Microsoft Graph API',
                'details' => $sendMailResponse->json(),
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => "Email sent successfully from {$senderEmail} to {$recipientEmail}",
        ]);
    }
}
