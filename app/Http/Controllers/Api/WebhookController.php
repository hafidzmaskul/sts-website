<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class WebhookController extends Controller
{
    public function handleSquare(Request $request)
    {
        $payload = $request->all();
        $type = $payload['type'] ?? null;

        if (! $type) {
            return response()->json(['message' => 'No event type'], 400);
        }

        Log::info('Square Webhook Received: '.$type, $payload);

        $data = $payload['data']['object']['invoice'] ?? null;

        if (! $data) {
            return response()->json(['message' => 'No invoice data'], 200);
        }

        $squareInvoiceId = $data['id'];
        $status = $data['status'] ?? null;

        $transaction = Transaction::where('square_payment_id', $squareInvoiceId)->first();

        if (! $transaction) {
            Log::warning('Square Webhook: Transaction not found for Invoice ID: '.$squareInvoiceId);

            return response()->json(['message' => 'Transaction not found'], 200);
        }

        switch ($type) {
            case 'invoice.payment_made':
                if ($status === 'PAID' && $transaction->status !== 'paid') {
                    $this->markAsPaid($transaction);
                }
                break;

            case 'invoice.canceled':
            case 'invoice.payment_failed':
                if ($transaction->status !== 'paid') {
                    $transaction->update(['status' => 'failed']);
                    Log::info("Transaction {$transaction->invoice_code} marked as failed.");
                }
                break;
        }

        return response()->json(['message' => 'Webhook processed']);
    }

    private function markAsPaid(Transaction $transaction)
    {
        $transaction->update(['status' => 'paid']);
        Log::info("Transaction {$transaction->invoice_code} marked as PAID.");

        $this->sendPaidEmail($transaction);
    }

    private function sendPaidEmail(Transaction $transaction)
    {
        try {
            // 1. Get the full system path to the file
            $filePath = $transaction->product_attachment_snapshot
                ? Storage::disk('public')->path($transaction->product_attachment_snapshot)
                : null;

            // 2. Update the message body
            $emailBody = implode("\n", [
                'Payment Confirmed',
                '=================',
                'Invoice: '.$transaction->invoice_code,
                '',
                'Thank you! We have received your payment of £'.number_format($transaction->total_amount, 2).'.',
                '',
                'YOUR PRODUCT',
                '---------------------',
                'Please find your product file attached to this email.',
                '',
                'Thank you for your business.',
            ]);

            // 3. Attach the file in the callback
            Mail::raw($emailBody, function ($m) use ($transaction, $filePath) {
                $m->to($transaction->email)
                    ->subject('Receipt & Product: '.$transaction->product_name_snapshot);

                // Only attach if the file actually exists on the server
                if ($filePath && file_exists($filePath)) {
                    $m->attach($filePath);
                }
            });

            Log::info("Paid email with attachment sent to {$transaction->email}");

        } catch (\Throwable $e) {
            Log::error('Failed to send paid email: '.$e->getMessage());
        }
    }
}
