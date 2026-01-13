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
            ->with(['items'])
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
            ->with(['items.product'])
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
            'shipping_price' => 'required|numeric',
            'tax_amount' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'quote_builder_ids' => 'nullable|array',
            'quote_builder_ids.*' => 'exists:quote_builders,id',
        ]);

        // Validate Payment & Shipping Methods against Settings
        if ($request->filled('shipping_method') || $request->filled('shipping_payment_method')) {
            $keys = [
                'shipping_method_1_name',
                'shipping_method_2_name',
                'shipping_method_3_name',
                'payment_method_1_name',
                'payment_method_2_name',
                'payment_method_3_name',
            ];
            $settings = \App\Models\Setting::whereIn('key', $keys)->pluck('value', 'key');

            if ($request->filled('shipping_method')) {
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

            if ($request->filled('shipping_payment_method')) {
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

        // Create Transaction
        $transaction = new Transaction();
        $transaction->invoice_code = $invoiceCode;
        $transaction->customer_id = $user->customer->id;
        $transaction->subtotal = $request->total_amount - $request->tax_amount - $request->shipping_price;
        $transaction->tax_amount = $request->tax_amount;
        $transaction->total_amount = $request->total_amount;
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
        $transaction->shipping_price = $request->shipping_price;

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

        return response()->json([
            'success' => true,
            'data' => $transaction->load('items'),
        ], 201);
    }
}