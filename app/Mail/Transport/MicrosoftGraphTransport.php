<?php

namespace App\Mail\Transport;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

class MicrosoftGraphTransport extends AbstractTransport
{
    /**
     * Create a new Microsoft Graph mail transport instance.
     */
    public function __construct(
        public string $tenantId,
        public string $clientId,
        public string $clientSecret,
        ?EventDispatcherInterface $dispatcher = null,
        ?LoggerInterface $logger = null
    ) {
        parent::__construct($dispatcher, $logger);
    }

    /**
     * Send the message.
     */
    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $from = $email->getFrom();
        $senderEmail = count($from) > 0 ? $from[0]->getAddress() : config('mail.from.address');

        $token = $this->getAccessToken();
        $payload = $this->buildPayload($email);

        $sendMailUrl = "https://graph.microsoft.com/v1.0/users/{$senderEmail}/sendMail";
        $response = Http::withToken($token)->post($sendMailUrl, $payload);

        // If unauthorized, token might have expired, clear cache and retry once
        if ($response->status() === 401) {
            Cache::forget('microsoft_graph_token');
            $token = $this->getAccessToken();
            $response = Http::withToken($token)->post($sendMailUrl, $payload);
        }

        if ($response->failed()) {
            throw new \Exception('Failed to send email via Microsoft Graph: '.$response->body());
        }
    }

    /**
     * Get the Microsoft Graph access token.
     */
    protected function getAccessToken(): string
    {
        return Cache::remember('microsoft_graph_token', 3000, function (): string {
            $tokenUrl = "https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token";

            $tokenResponse = Http::asForm()->post($tokenUrl, [
                'client_id' => $this->clientId,
                'scope' => 'https://graph.microsoft.com/.default',
                'client_secret' => $this->clientSecret,
                'grant_type' => 'client_credentials',
            ]);

            if ($tokenResponse->failed()) {
                throw new \Exception('Failed to obtain access token: '.$tokenResponse->body());
            }

            return $tokenResponse->json()['access_token'];
        });
    }

    /**
     * Build the request payload for Microsoft Graph sendMail.
     *
     * @param  \Symfony\Component\Mime\Email  $email
     */
    protected function buildPayload($email): array
    {
        $toRecipients = [];
        foreach ($email->getTo() as $address) {
            $toRecipients[] = [
                'emailAddress' => [
                    'address' => $address->getAddress(),
                    'name' => $address->getName() ?: null,
                ],
            ];
        }

        $ccRecipients = [];
        foreach ($email->getCc() as $address) {
            $ccRecipients[] = [
                'emailAddress' => [
                    'address' => $address->getAddress(),
                    'name' => $address->getName() ?: null,
                ],
            ];
        }

        $bccRecipients = [];
        foreach ($email->getBcc() as $address) {
            $bccRecipients[] = [
                'emailAddress' => [
                    'address' => $address->getAddress(),
                    'name' => $address->getName() ?: null,
                ],
            ];
        }

        $html = $email->getHtmlBody();
        $text = $email->getTextBody();

        if ($html) {
            $body = [
                'contentType' => 'HTML',
                'content' => $html,
            ];
        } else {
            $body = [
                'contentType' => 'Text',
                'content' => $text ?? '',
            ];
        }

        $messageData = [
            'subject' => $email->getSubject(),
            'body' => $body,
            'toRecipients' => $toRecipients,
        ];

        if (! empty($ccRecipients)) {
            $messageData['ccRecipients'] = $ccRecipients;
        }

        if (! empty($bccRecipients)) {
            $messageData['bccRecipients'] = $bccRecipients;
        }

        $attachments = [];
        foreach ($email->getAttachments() as $attachment) {
            $attachments[] = [
                '@odata.type' => '#microsoft.graph.fileAttachment',
                'name' => $attachment->getFilename(),
                'contentType' => $attachment->getContentType(),
                'contentBytes' => base64_encode($attachment->getBody()),
            ];
        }

        if (! empty($attachments)) {
            $messageData['attachments'] = $attachments;
        }

        return [
            'message' => $messageData,
            'saveToSentItems' => 'false',
        ];
    }

    /**
     * Get the string representation of the transport.
     */
    public function __toString(): string
    {
        return 'microsoft-graph';
    }
}
