<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class SquareInvoiceService
{
    /**
     * Create a Square invoice and return metadata including public_url.
     *
     * @param  string $buyerName
     * @param  string $buyerEmail
     * @param  float  $amountDecimal     e.g., 100.50
     * @param  string $currency          ISO-4217, e.g., "USD", "EUR", "IDR"
     * @param  string $title             Invoice title
     * @param  string $description       Invoice description
     * @param  int    $dueDays           Days from now for due date (default 7)
     * @return array{invoice_url?:string, invoice_id?:string, order_id?:string, customer_id?:string, status?:string, raw?:mixed}
     *
     * @throws \RuntimeException on failure
     */
    public function createInvoice(
        string $buyerName,
        string $buyerEmail,
        float $amountDecimal,
        string $currency,
        string $title = 'Invoice',
        string $description = '',
        int $dueDays = 7
    ): array {
        $token      = env('SQUARE_ACCESS_TOKEN');
        $locationId = env('SQUARE_LOCATION_ID');
        $env        = strtolower(env('SQUARE_ENV', 'sandbox'));
        $apiVersion = env('SQUARE_VERSION'); // optional

        if (!$token || !$locationId) {
            throw new \RuntimeException('Missing SQUARE_ACCESS_TOKEN or SQUARE_LOCATION_ID.');
        }

        $base = $env === 'sandbox'
            ? 'https://connect.squareupsandbox.com/v2'
            : 'https://connect.squareup.com/v2';

        $headers = [
            'Authorization' => 'Bearer '.$token,
            'Content-Type'  => 'application/json',
        ];
        if (!empty($apiVersion)) {
            $headers['Square-Version'] = $apiVersion;
        }

        // Convert to minor units (cents). Good default; adjust if you need currency-specific scaling.
        $amountMinor = (int) round($amountDecimal * 100);

        // Split buyer name
        $parts       = preg_split('/\s+/', trim($buyerName), 2);
        $givenName   = $parts[0] ?? $buyerName;
        $familyName  = $parts[1] ?? '';

        // 1) Create customer
        $createCustomer = Http::withHeaders($headers)->post("$base/customers", [
            'idempotency_key' => (string) Str::uuid(),
            'given_name'      => $givenName,
            'family_name'     => $familyName,
            'email_address'   => $buyerEmail,
        ]);

        if (!$createCustomer->successful()) {
            throw new \RuntimeException('Create customer failed: '.$createCustomer->body());
        }

        $customerId = data_get($createCustomer->json(), 'customer.id');
        if (!$customerId) {
            throw new \RuntimeException('No customer ID returned.');
        }

        // 2) Create order
        $createOrder = Http::withHeaders($headers)->post("$base/orders", [
            'idempotency_key' => (string) Str::uuid(),
            'order' => [
                'location_id' => $locationId,
                'customer_id' => $customerId,
                'line_items'  => [[
                    'name'             => $title,
                    'quantity'         => '1',
                    'base_price_money' => ['amount' => $amountMinor, 'currency' => strtoupper($currency)],
                ]],
            ],
        ]);

        if (!$createOrder->successful()) {
            throw new \RuntimeException('Create order failed: '.$createOrder->body());
        }

        $orderId = data_get($createOrder->json(), 'order.id');
        if (!$orderId) {
            throw new \RuntimeException('No order ID returned.');
        }

        // 3) Create DRAFT invoice
        $dueDate = Carbon::now()->addDays($dueDays)->format('Y-m-d');
        $createInvoice = Http::withHeaders($headers)->post("$base/invoices", [
            'idempotency_key' => (string) Str::uuid(),
            'invoice' => [
                'location_id'  => $locationId,
                'order_id'     => $orderId,
                'title'        => $title,
                'description'  => $description,
                'primary_recipient' => [
                    'customer_id' => $customerId,
                ],
                'accepted_payment_methods' => [
                    'card'              => true,
                    'bank_account'      => true,
                    'buy_now_pay_later' => true,
                    'cash_app_pay'      => true,
                    'square_gift_card'  => true,
                ],
                'store_payment_method_enabled' => true,
                'payment_requests' => [[
                    'request_type' => 'BALANCE',
                    'due_date'     => $dueDate,
                ]],
                'delivery_method' => 'EMAIL',
            ],
        ]);

        if (!$createInvoice->successful()) {
            throw new \RuntimeException('Create invoice failed: '.$createInvoice->body());
        }

        $invoice   = data_get($createInvoice->json(), 'invoice');
        $invoiceId = data_get($invoice, 'id');
        $version   = data_get($invoice, 'version');

        if (!$invoiceId) {
            throw new \RuntimeException('No invoice ID returned.');
        }

        // 4) Publish invoice
        $publish = Http::withHeaders($headers)->post("$base/invoices/{$invoiceId}/publish", [
            'idempotency_key' => (string) Str::uuid(),
            'version'         => $version,
        ]);

        if (!$publish->successful()) {
            throw new \RuntimeException('Publish invoice failed: '.$publish->body());
        }

        $published = data_get($publish->json(), 'invoice');

        return [
            'invoice_url' => data_get($published, 'public_url'),
            'invoice_id'  => data_get($published, 'id'),
            'order_id'    => $orderId,
            'customer_id' => $customerId,
            'status'      => data_get($published, 'status'),
            'raw'         => $published,
        ];
    }
}
