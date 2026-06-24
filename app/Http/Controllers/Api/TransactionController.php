<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TransactionController extends Controller
{
    /**
     * Display a listing of the authenticated customer's transactions.
     */
    public function summary(Request $request)
    {
        $user = $request->user();
        if (! $user->customer) {
            return response()->json([
                'success' => false,
                'message' => 'User is not a customer.',
            ], 403);
        }

        $query = Transaction::query();

        // 1. Determine Scope based on Account Level
        $isHead = $user->customer->account_level === 'head';

        if ($isHead && $user->customer->company_id) {
            // Head Account: View all transactions for their Company
            // We need to find all customers belonging to this company
            $companyId = $user->customer->company_id;
            $customerIds = \App\Models\Customer::where('company_id', $companyId)->pluck('id');
            $query->whereIn('customer_id', $customerIds);
        } else {
            // Staff Account (or no company): View only their own transactions
            $query->where('customer_id', $user->customer->id);
        }

        // 2. Clone query for Status Counts (before applying other filters if any)
        $statusQuery = clone $query;
        $statusCounts = $statusQuery->select('status', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // 3. Get Transactions
        // Optional: Apply status filter if requested for the list itself (though not explicitly asked, it is good practice, but I will stick to the plan)
        // The user asked "get all transaction", so we return them.

        $transactions = $query->with(['items.product.images'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => $statusCounts,
                'transactions' => $transactions,
            ],
        ]);
    }

    /**
     * Display a listing of the authenticated customer's transactions.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (! $user->customer) {
            return response()->json([
                'success' => false,
                'message' => 'User is not a customer.',
            ], 403);
        }

        $query = Transaction::where('customer_id', $user->customer->id)
            ->with(['items.product.images'])
            ->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $statuses = $request->status;
            if (is_string($statuses)) {
                $statuses = explode(',', $statuses);
            }
            if (is_array($statuses)) {
                $query->whereIn('status', $statuses);
            }
        }

        $transactions = $query->get();

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
        if (! $user->customer) {
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
        if (! $user->customer) {
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
            'product_id' => 'nullable|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'coupon_id' => 'nullable|exists:coupons,id',
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
                if (! $user->hasRole('credit facilities account')) {
                    $validShippingMethods = [];
                    for ($i = 1; $i <= 3; $i++) {
                        if (! empty($settings["shipping_method_{$i}_name"])) {
                            $validShippingMethods[] = $settings["shipping_method_{$i}_name"];
                        }
                    }

                    if (! in_array($request->shipping_method, $validShippingMethods)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid shipping method.',
                        ], 400);
                    }
                }
            }

            if ($request->filled('shipping_payment_method')) {
                // Bypass validation for credit facilities account
                if (! $user->hasRole('credit facilities account')) {
                    $validPaymentMethods = [];
                    for ($i = 1; $i <= 3; $i++) {
                        if (! empty($settings["payment_method_{$i}_name"])) {
                            $validPaymentMethods[] = $settings["payment_method_{$i}_name"];
                        }
                    }

                    if (! in_array($request->shipping_payment_method, $validPaymentMethods)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid payment method.',
                        ], 400);
                    }
                }
            }
        }

        // Generate Invoice Code
        $invoiceCode = 'INV-'.strtoupper(uniqid());

        // Generate Order Code
        $orderCode = Transaction::generateOrderCode();

        // Prepare items data to process later
        $itemsToProcess = [];
        $isQuoteBuilder = false;
        $isDirectPurchase = false;

        if ($request->filled('product_id')) {
            $isDirectPurchase = true;
            $product = \App\Models\Product::find($request->product_id);
            $quantity = $request->input('quantity', 1);

            // Create a mock object that mimics a cart item structure for consistent processing
            // Alternatively, just treat it as a distinct case.
            // Let's create a generic structure to iterate over.
            $itemsToProcess[] = (object) [
                'product' => $product,
                'quantity' => $quantity,
            ];

        } elseif ($request->has('quote_builder_ids') && ! empty($request->quote_builder_ids)) {
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
        $debugItems = [];
        \Illuminate\Support\Facades\Log::info('--- Transaction Total Calculation Start ---');
        foreach ($itemsToProcess as $item) {
            if ($isQuoteBuilder) {
                // Quote Builder Logic
                foreach ($item->products as $product) {
                    $quantity = 1;
                    if ($product->pivot && isset($product->pivot->quantity)) {
                        $quantity = $product->pivot->quantity;
                    }
                    $price = $product->calculatePrice($user) ?? 0;
                    $subtotalItem = $price * $quantity;
                    $calculatedSubtotal += $subtotalItem;

                    $debugItems[] = [
                        'product_id' => $product->id,
                        'title' => $product->title,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotalItem,
                    ];

                    \Illuminate\Support\Facades\Log::info("Quote Item: {$product->title}, Price: {$price}, Qty: {$quantity}, Subtotal: {$subtotalItem}");
                }
            } else {
                // Cart Logic
                $product = $item->product;
                $price = $product->calculatePrice($user) ?? 0;
                $subtotalItem = $price * $item->quantity;
                $calculatedSubtotal += $subtotalItem;

                $debugItems[] = [
                    'product_id' => $product->id,
                    'title' => $product->title,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'subtotal' => $subtotalItem,
                ];

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

        // Calculate Subtotal + Shipping before discount
        $subtotalWithShipping = $calculatedSubtotal + $calculatedShippingPrice;

        // 4. Calculate Discount
        $calculatedDiscount = 0;
        $coupon = null;
        $couponData = null;

        if ($request->filled('coupon_id')) {
            $coupon = \App\Models\Coupon::find($request->coupon_id);

            if (! $coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid coupon code.',
                ], 400);
            }

            if (! $coupon->isEligibleFor($user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Coupon is not valid or you are not eligible.',
                ], 400);
            }

            // Calculate Discount
            if ($coupon->discount_type === 'percentage') {
                $calculatedDiscount = $calculatedSubtotal * ($coupon->discount_value / 100);
            } else {
                $calculatedDiscount = $coupon->discount_value;
            }

            // Ensure discount doesn't exceed subtotal (optional rule, but good practice)
            // Or should it cover shipping? Usually coupons cover items.
            // Let's assume it covers subtotal.
            if ($calculatedDiscount > $calculatedSubtotal) {
                $calculatedDiscount = $calculatedSubtotal;
            }

            $couponData = [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'discount_amount' => $calculatedDiscount,
            ];
        }

        // Apply Discount to Taxable Base?
        // Typically tax is applied AFTER discount.
        // Taxable Amount = (Subtotal - Discount) + Shipping
        // Let's adjust tax calculation.

        $taxableAmount = max(0, $calculatedSubtotal - $calculatedDiscount) + $calculatedShippingPrice;
        $calculatedTaxAmount = $taxableAmount * ($taxRate / 100);

        // 5. Calculate Total
        $calculatedTotal = max(0, $calculatedSubtotal - $calculatedDiscount) + $calculatedShippingPrice + $calculatedTaxAmount;

        \Illuminate\Support\Facades\Log::info("Calculated Subtotal: {$calculatedSubtotal}");
        \Illuminate\Support\Facades\Log::info("Calculated Shipping Price: {$calculatedShippingPrice}");
        \Illuminate\Support\Facades\Log::info("Calculated Discount: {$calculatedDiscount}");
        \Illuminate\Support\Facades\Log::info("Tax Rate: {$taxRate}%");
        \Illuminate\Support\Facades\Log::info("Calculated Tax Amount: {$calculatedTaxAmount}");
        \Illuminate\Support\Facades\Log::info("Calculated Total: {$calculatedTotal}");
        \Illuminate\Support\Facades\Log::info("Request Total: {$request->total_amount}");
        \Illuminate\Support\Facades\Log::info('--- Transaction Total Calculation End ---');

        // Epsilon check for float comparison (allow 0.05 difference)
        if (abs($calculatedTotal - $request->total_amount) > 0.05) {
            return response()->json([
                'success' => false,
                'message' => 'Total amount mismatch. Calculated: '.number_format($calculatedTotal, 2).', Request: '.number_format($request->total_amount, 2),
                'data' => [
                    'items' => $debugItems,
                    'shipping' => [
                        'method' => $request->shipping_method,
                        'price' => $calculatedShippingPrice,
                    ],
                    'coupon' => $couponData,
                    'tax' => [
                        'rate' => $taxRate,
                        'amount' => $calculatedTaxAmount,
                    ],
                    'totals' => [
                        'subtotal' => $calculatedSubtotal,
                        'discount_amount' => $calculatedDiscount,
                        'shipping_price' => $calculatedShippingPrice,
                        'tax_amount' => $calculatedTaxAmount,
                        'calculated_total' => $calculatedTotal,
                        'request_total' => $request->total_amount,
                    ],
                ],
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
                    'message' => 'Insufficient credit balance. Current balance: '.number_format($currentBalance, 2),
                ], 400);
            }
        }

        // Create Transaction
        $transaction = new Transaction;
        $transaction->invoice_code = $invoiceCode;
        $transaction->order_code = $orderCode;
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
        if ($user->hasRole('credit facilities account')) {
            $transaction->payment_method = 'credit_limit';
        } else {
            $transaction->payment_method = $request->shipping_payment_method;
        }

        if ($coupon) {
            $transaction->coupon_id = $coupon->id;
            $transaction->discount_amount = $calculatedDiscount;

            // Store Historical Data Snapshot
            $transaction->coupon_data = [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'name' => $coupon->name,
                'type' => $coupon->type,
                'discount_type' => $coupon->discount_type,
                'discount_value' => $coupon->discount_value,
            ];

            // Increment Used Count
            $coupon->increment('used_count');
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

                    $price = $product->calculatePrice($user) ?? 0;
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
                $price = $product->calculatePrice($user) ?? 0;

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
                if (! $isDirectPurchase) {
                    $item->delete();
                }
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
                'description' => 'Transaction '.$transaction->invoice_code,
                'transaction_id' => $transaction->id,
                'user_id' => $user->id,
            ]);
        }

        $this->sendNotificationEmail($transaction);

        return response()->json([
            'success' => true,
            'data' => $transaction->load(['items.product.images']),
        ], 201);
    }

    private function sendNotificationEmail(Transaction $transaction)
    {
        $adminEmailSetting = Setting::where('key', 'email_notification_admin')->first();
        $adminEmail = $adminEmailSetting ? $adminEmailSetting->value : config('mail.from.address');

        if (! $adminEmail) {
            return;
        }

        $emailBody = implode("\n", [
            'New Order Received',
            '==================',
            '',
            'Invoice Code : '.$transaction->invoice_code,
            'Total Amount : '.number_format($transaction->total_amount, 2),
            'Customer     : '.$transaction->shipping_first_name.' '.$transaction->shipping_last_name,
            'Email        : '.$transaction->contact_email,
            '',
            'Date: '.now()->format('Y-m-d H:i:s'),
        ]);

        try {
            Mail::raw($emailBody, function ($m) use ($adminEmail, $transaction) {
                $m->to($adminEmail)
                    ->replyTo($transaction->contact_email, $transaction->shipping_first_name.' '.$transaction->shipping_last_name)
                    ->subject('New Order: '.$transaction->invoice_code);
            });
        } catch (\Throwable $e) {
            Log::error('Failed to send transaction notification email: '.$e->getMessage());
        }
    }
}
