<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Transaction;
use App\Services\Payments\SquareInvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function store(Request $request, SquareInvoiceService $square)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'first_name' => ['required', 'string', 'max:150'],
            'last_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:191'],
            'mobile_phone' => ['required', 'string', 'max:30'],
            'town_city' => ['required', 'string', 'max:191'],
            'postcode' => ['required', 'string', 'max:30'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        $amountDecimal = (float) $product->price;

        $vatSetting = Setting::where('key', 'vat_percentage')->first();
        $vatPercentage = $vatSetting ? (float) $vatSetting->value : 0.0;
        $vatRate = $vatPercentage / 100;

        $vatDecimal = $amountDecimal * $vatRate;
        $totalAmountDecimal = $amountDecimal + $vatDecimal;
        #$currency           = 'GBP';
        $currency = 'USD';
        $invoiceDueDays = 1;

        $customerFullName = trim($validated['first_name'] . ' ' . $validated['last_name']);
        $title = 'Order for: ' . $product->name;
        $description = "Product: {$product->name}\nCustomer: {$customerFullName}";

        $snapshotPath = null;
        if ($product->attachment && Storage::disk('public')->exists($product->attachment)) {
            $extension = pathinfo($product->attachment, PATHINFO_EXTENSION);
            $newFilename = 'transaction_' . Str::uuid() . '.' . $extension;
            $snapshotPath = 'transactions/attachments/' . $newFilename;
            Storage::disk('public')->copy($product->attachment, $snapshotPath);
        }

        $invoiceUrl = null;
        $squareInvoiceId = null;

        try {
            $res = $square->createInvoice(
                buyerName: $customerFullName,
                buyerEmail: $validated['email'],
                amountDecimal: $totalAmountDecimal,
                currency: $currency,
                title: $title,
                description: $description,
                dueDays: $invoiceDueDays
            );

            $invoiceUrl = $res['invoice_url'] ?? null;
            $squareInvoiceId = $res['invoice_id'] ?? null;

        } catch (\Throwable $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create invoice via Square.',
                'error' => $ex->getMessage(),
            ], 502);
        }

        $invoiceCode = $this->generateUniqueInvoiceCode();

        try {
            $transaction = Transaction::create([
                'invoice_code' => $invoiceCode,
                'product_id' => $product->id,
                'product_name_snapshot' => $product->name,
                'product_attachment_snapshot' => $snapshotPath,
                'price' => $amountDecimal,
                'vat_amount' => $vatDecimal,
                'total_amount' => $totalAmountDecimal,
                'status' => 'pending',
                'square_payment_id' => $squareInvoiceId,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'mobile_phone' => $validated['mobile_phone'],
                'town_city' => $validated['town_city'],
                'postcode' => $validated['postcode'],
            ]);

            $this->sendNotifications($transaction, $product, $invoiceUrl, $vatPercentage);

            return response()->json([
                'success' => true,
                'message' => 'Transaction initiated.',
                'redirect_url' => $invoiceUrl,
                'data' => $transaction,
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save transaction.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function generateUniqueInvoiceCode(): string
    {
        do {
            $code = 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
        } while (Transaction::where('invoice_code', $code)->exists());

        return $code;
    }

    private function sendNotifications($transaction, $product, $invoiceUrl, $vatPercentage)
    {
        $adminEmailSetting = Setting::where('key', 'admin_email')->first();
        $adminEmail = $adminEmailSetting ? $adminEmailSetting->value : config('mail.from.address');

        $emailBody = implode("\n", [
            "New Order Created",
            "=================",
            "Invoice Code : " . $transaction->invoice_code,
            "Date         : " . $transaction->created_at->format('d M Y H:i'),
            "",
            "Customer Details",
            "----------------",
            "Name         : " . $transaction->full_name,
            "Email        : " . $transaction->email,
            "Phone        : " . $transaction->mobile_phone,
            "Location     : " . $transaction->town_city . ", " . $transaction->postcode,
            "",
            "Order Details",
            "-------------",
            "Product      : " . $product->name,
            "Price        : £" . number_format($transaction->price, 2),
            "VAT ({$vatPercentage}%)    : £" . number_format($transaction->vat_amount, 2),
            "Total        : £" . number_format($transaction->total_amount, 2),
            "",
            "Payment Link : " . ($invoiceUrl ?? 'N/A'),
            "Status       : " . ucfirst($transaction->status),
        ]);

        if ($adminEmail) {
            try {
                Mail::raw($emailBody, function ($m) use ($adminEmail, $transaction) {
                    $m->to($adminEmail)
                        ->subject('New Order Alert: ' . $transaction->invoice_code);
                });
            } catch (\Throwable $e) {
                Log::error('Failed to send admin email: ' . $e->getMessage());
            }
        }

        try {
            $customerSubject = 'Your Order Invoice: ' . $transaction->invoice_code;
            $customerBody = "Dear " . $transaction->first_name . ",\n\n" .
                "Thank you for your order. Please find your invoice details below.\n\n" .
                "IMPORTANT: Please click the link below to complete your payment via Square:\n" .
                ($invoiceUrl ?? 'Link generating...') . "\n\n" .
                $emailBody;

            Mail::raw($customerBody, function ($m) use ($transaction, $customerSubject) {
                $m->to($transaction->email)
                    ->subject($customerSubject);
            });
        } catch (\Throwable $e) {
            Log::error('Failed to send customer email: ' . $e->getMessage());
        }
    }
}