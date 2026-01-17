<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the authenticated customer's transactions.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user->customer) {
            return response()->json([
                'success' => false,
                'message' => 'User is not a customer.',
            ], 403);
        }

        $transactions = Transaction::where('customer_id', $user->customer->id)
            ->with(['items.product.images'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }

    /**
     * Display the specified transaction.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        if (!$user->customer) {
            return response()->json([
                'success' => false,
                'message' => 'User is not a customer.',
            ], 403);
        }

        $transaction = Transaction::where('customer_id', $user->customer->id)
            ->with(['items.product.images'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $transaction,
        ]);
    }
    /**
     * Store a new transaction.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user->customer) {
            return response()->json([
                'success' => false,
                'message' => 'User is not a customer.',
            ], 403);
        }

        $request->validate([
            'contact_email' => 'required|email',
            'shipping_first_name' => 'nullable|string',
            'shipping_last_name' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'shipping_city' => 'nullable|string',
            'shipping_postal_code' => 'nullable|string',
            'shipping_country' => 'nullable|string',
            'shipping_phone_number' => 'nullable|string',
            'shipping_payment_method' => 'nullable|string',
            'shipping_method' => 'nullable|string',
            // 'shipping_price' => 'required|numeric', // Removed
            // 'tax_amount' => 'required|numeric', // Removed
            'total_amount' => 'required|numeric',
            'quote_builder_ids' => 'nullable|array',
            'quote_builder_ids.*' => 'exists:quote_builders,id',
        ]);

        // Load Settings for Calculation
        $keys = [
            'shipping_method_1_name',
            'shipping_method_1_price',
            'shipping_method_2_name',
            'shipping_method_2_price',
            'shipping_method_3_name',
            'shipping_method_3_price',
            'payment_method_1_name',
            'payment_method_2_name',
            'payment_method_3_name',
            'transaction_tax',
        ];
        $settings = \App\Models\Setting::whereIn('key', $keys)->pluck('value', 'key');

        // Validate Payment & Shipping Methods against Settings
        if ($request->filled('shipping_method') || $request->filled('shipping_payment_method')) {
            if ($request->filled('shipping_method')) {
                // Bypass validation for credit facilities account
                if (!$user->hasRole('credit facilities account')) {
                    $validShippingMethods = [];
                    for ($i = 1; $i <= 3; $i++) {
                        if (!empty($settings["shipping_method_{$i}_name"])) {
                            $validShippingMethods[] = $settings["shipping_method_{$i}_name"];
                        }
                    }

                    if (!in_array($request->shipping_method, $validShippingMethods)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid shipping method.',
                        ], 400);
                    }
                }
            }

            if ($request->filled('shipping_payment_method')) {
                // Bypass validation for credit facilities account
                if (!$user->hasRole('credit facilities account')) {
                    $validPaymentMethods = [];
                    for ($i = 1; $i <= 3; $i++) {
                        if (!empty($settings["payment_method_{$i}_name"])) {
                            $validPaymentMethods[] = $settings["payment_method_{$i}_name"];
                        }
                    }

                    if (!in_array($request->shipping_payment_method, $validPaymentMethods)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid payment method.',
                        ], 400);
                    }
                }
            }
        }

        // Generate Invoice Code
        $invoiceCode = 'INV-' . strtoupper(uniqid());

        // Prepare items data to process later
        $itemsToProcess = [];
        $isQuoteBuilder = false;

        if ($request->has('quote_builder_ids') && !empty($request->quote_builder_ids)) {
            $isQuoteBuilder = true;
            $itemsToProcess = \App\Models\QuoteBuilder::whereIn('id', $request->quote_builder_ids)
                ->where('user_id', $user->id)
                ->with('products')
                ->get();

            if ($itemsToProcess->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quote builders not found or access denied.',
                ], 404);
            }

            // Optional: strict check if count matches. Assuming request IDs are unique.
            if ($itemsToProcess->count() !== count(array_unique($request->quote_builder_ids))) {
                return response()->json([
                    'success' => false,
                    'message' => 'One or more quote builders not found.',
                ], 404);
            }

        } else {
            $itemsToProcess = $user->cartItems()->with('product')->get();

            if ($itemsToProcess->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty.',
                ], 400);
            }
        }

        // 1. Calculate Subtotal
        $calculatedSubtotal = 0;
        \Illuminate\Support\Facades\Log::info('--- Transaction Total Calculation Start ---');
        foreach ($itemsToProcess as $item) {
            if ($isQuoteBuilder) {
                // Quote Builder Logic
                foreach ($item->products as $product) {
                    $quantity = 1;
                    if ($product->pivot && isset($product->pivot->quantity)) {
                        $quantity = $product->pivot->quantity;
                    }
                    $price = $product->calculatePrice($user) ?? ($product->special_price ?: $product->base_price);
                    $subtotalItem = $price * $quantity;
                    $calculatedSubtotal += $subtotalItem;
                    \Illuminate\Support\Facades\Log::info("Quote Item: {$product->title}, Price: {$price}, Qty: {$quantity}, Subtotal: {$subtotalItem}");
                }
            } else {
                // Cart Logic
                $product = $item->product;
                $price = $product->calculatePrice($user) ?? ($product->special_price ?: $product->base_price);
                $subtotalItem = $price * $item->quantity;
                $calculatedSubtotal += $subtotalItem;
                \Illuminate\Support\Facades\Log::info("Cart Item: {$product->title}, Price: {$price}, Qty: {$item->quantity}, Subtotal: {$subtotalItem}");
            }
        }

        // 2. Calculate Shipping Price
        $calculatedShippingPrice = 0;
        if ($request->filled('shipping_method')) {
            for ($i = 1; $i <= 3; $i++) {
                if (($settings["shipping_method_{$i}_name"] ?? '') === $request->shipping_method) {
                    $calculatedShippingPrice = (float) ($settings["shipping_method_{$i}_price"] ?? 0);
                    break;
                }
            }
        }
        // Fallback for credit facilities if method not found in standard settings?
        // For now, if not matched, it remains 0.

        // 3. Calculate Tax
        $taxRate = (float) ($settings['transaction_tax'] ?? 0);
        $calculatedTaxAmount = ($calculatedSubtotal + $calculatedShippingPrice) * ($taxRate / 100);

        // 4. Calculate Total
        $calculatedTotal = $calculatedSubtotal + $calculatedShippingPrice + $calculatedTaxAmount;

        \Illuminate\Support\Facades\Log::info("Calculated Subtotal: {$calculatedSubtotal}");
        \Illuminate\Support\Facades\Log::info("Calculated Shipping Price: {$calculatedShippingPrice}");
        \Illuminate\Support\Facades\Log::info("Tax Rate: {$taxRate}%");
        \Illuminate\Support\Facades\Log::info("Calculated Tax Amount: {$calculatedTaxAmount}");
        \Illuminate\Support\Facades\Log::info("Calculated Total: {$calculatedTotal}");
        \Illuminate\Support\Facades\Log::info("Request Total: {$request->total_amount}");
        \Illuminate\Support\Facades\Log::info('--- Transaction Total Calculation End ---');

        // Epsilon check for float comparison (allow 0.05 difference)
        if (abs($calculatedTotal - $request->total_amount) > 0.05) {
            return response()->json([
                'success' => false,
                'message' => 'Total amount mismatch. Calculated: ' . number_format($calculatedTotal, 2) . ', Request: ' . number_format($request->total_amount, 2),
            ], 400);
        }

        // Validate Credit Limit Balance for Credit Facilities Account
        if ($user->hasRole('credit facilities account')) {
            $latestCreditLimit = \App\Models\CreditLimit::where('company_id', $user->customer?->company_id)
                ->latest()
                ->first();

            $currentBalance = $latestCreditLimit ? $latestCreditLimit->balance : 0;

            if ($currentBalance < $calculatedTotal) { // Use calculated total
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient credit balance. Current balance: ' . number_format($currentBalance, 2),
                ], 400);
            }
        }

        // Create Transaction
        $transaction = new Transaction();
        $transaction->invoice_code = $invoiceCode;
        $transaction->customer_id = $user->customer->id;
        $transaction->subtotal = $calculatedSubtotal;
        $transaction->tax_amount = $calculatedTaxAmount;
        $transaction->total_amount = $calculatedTotal;
        $transaction->status = 'pending';
        $transaction->contact_email = $request->contact_email;
        $transaction->shipping_first_name = $request->shipping_first_name;
        $transaction->shipping_last_name = $request->shipping_last_name;
        $transaction->shipping_address = $request->shipping_address;
        $transaction->shipping_city = $request->shipping_city;
        $transaction->shipping_postal_code = $request->shipping_postal_code;
        $transaction->shipping_country = $request->shipping_country;
        $transaction->shipping_phone_number = $request->shipping_phone_number;
        $transaction->shipping_method = $request->shipping_method;
        $transaction->shipping_price = $calculatedShippingPrice;
        // Auto set payment method for credit facilities
        if ($user->hasRole('credit facilities account')) {
            $transaction->payment_method = 'credit_limit';
        } else {
            $transaction->payment_method = $request->shipping_payment_method;
        }

        $transaction->save();
        $subtotal = 0;

        if ($isQuoteBuilder) {
            // Quote Builder Scenario
            foreach ($itemsToProcess as $qb) {
                foreach ($qb->products as $product) {
                    $quantity = 1; // Default
                    if ($product->pivot && isset($product->pivot->quantity)) {
                        $quantity = $product->pivot->quantity;
                    }

                    $price = $product->calculatePrice($user) ?? ($product->special_price ?: $product->base_price);
                    $subtotal += $price * $quantity;

                    \App\Models\TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product->id,
                        'product_name_snapshot' => $product->title,
                        'quantity' => $quantity,
                        'unit_price' => $price,
                        'total_price' => $price * $quantity,
                    ]);
                }
            }
        } else {
            // Cart Scenario
            foreach ($itemsToProcess as $item) {
                $product = $item->product;
                $price = $product->calculatePrice($user) ?? ($product->special_price ?: $product->base_price);

                $subtotal += $price * $item->quantity;

                \App\Models\TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'product_name_snapshot' => $product->title,
                    'quantity' => $item->quantity,
                    'unit_price' => $price,
                    'total_price' => $price * $item->quantity,
                ]);

                // Delete Cart Item
                $item->delete();
            }
        }

        // Handle Credit Limit for Credit Facilities Account
        if ($user->hasRole('credit facilities account') && $user->customer && $user->customer->company_id) {
            $lastLimit = \App\Models\CreditLimit::where('company_id', $user->customer->company_id)
                ->latest()
                ->first();

            $previousBalance = $lastLimit ? $lastLimit->balance : 0;
            $newBalance = $previousBalance - $transaction->total_amount;

            \App\Models\CreditLimit::create([
                'customer_id' => $user->customer->id,
                'company_id' => $user->customer->company_id,
                'credit' => 0,
                'debit' => $transaction->total_amount,
                'balance' => $newBalance,
                'description' => 'Transaction ' . $transaction->invoice_code,
                'transaction_id' => $transaction->id,
                'user_id' => $user->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction->load('items.product.images'),
        ], 201);
    }
}