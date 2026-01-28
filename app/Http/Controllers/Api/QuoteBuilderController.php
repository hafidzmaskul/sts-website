<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuoteBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;

class QuoteBuilderController extends Controller
{
    public function index(Request $request)
    {
        $quoteBuilders = $request->user()->quoteBuilders()->with('products.images')->get();

        return response()->json([
            'data' => $quoteBuilders,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_id' => 'nullable|array',
            'product_id.*' => 'exists:products,id',
            'items' => 'nullable|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'nullable|integer|min:1',
        ]);

        $quoteBuilder = DB::transaction(function () use ($request) {
            $quoteBuilder = QuoteBuilder::create([
                'user_id' => $request->user()->id,
                'name' => $request->name,
            ]);

            if ($request->has('items')) {
                foreach ($request->items as $item) {
                    $quoteBuilder->products()->attach($item['product_id'], ['quantity' => $item['quantity'] ?? 1]);
                }
            } elseif ($request->has('product_id')) {
                $quoteBuilder->products()->attach($request->product_id, ['quantity' => 1]);
            }

            return $quoteBuilder;
        });

        // $this->sendNotificationEmail($quoteBuilder, 'created');

        return response()->json([
            'message' => 'Quote Builder created successfully',
            'data' => $quoteBuilder->load('products.images'),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $quoteBuilder = $request->user()->quoteBuilders()->findOrFail($id);

        $quoteBuilder->update([
            'name' => $request->name,
        ]);

        $this->sendNotificationEmail($quoteBuilder, 'updated');

        return response()->json([
            'message' => 'Quote Builder updated successfully',
            'data' => $quoteBuilder->load('products.images'),
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $quoteBuilder = $request->user()->quoteBuilders()->findOrFail($id);
        $quoteBuilder->delete();

        return response()->json([
            'message' => 'Quote Builder deleted successfully',
        ]);
    }

    public function addProduct(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quoteBuilder = $request->user()->quoteBuilders()->findOrFail($id);

        // Sync without detaching handles updates if the product exists, but using updateExistingPivot or syncWithoutDetaching with ID => attributes is safer for quantities
        // However, standard attach/sync might duplicate if we want multiple entries? Typically pivot is unique pair.
        // Assuming unique pair (product_id, quote_builder_id).

        $quantity = $request->quantity ?? 1;
        $quoteBuilder->products()->syncWithoutDetaching([
            $request->product_id => ['quantity' => $quantity]
        ]);

        return response()->json([
            'message' => 'Product added to Quote Builder successfully',
            'data' => $quoteBuilder->load('products.images'),
        ]);
    }

    public function removeProduct(Request $request, $id, $productId)
    {
        $quoteBuilder = $request->user()->quoteBuilders()->findOrFail($id);
        $quoteBuilder->products()->detach($productId);

        return response()->json([
            'message' => 'Product removed from Quote Builder successfully',
            'data' => $quoteBuilder->load('products.images'),
        ]);
    }

    /**
     * Get related products for a quote builder.
     * Returns products from the same categories as products already in the quote.
     * If no products in quote, returns latest products.
     * Supports search to find any product.
     */
    public function getRelatedProducts(Request $request, $id)
    {
        $quoteBuilder = $request->user()->quoteBuilders()->with('products.categories')->findOrFail($id);

        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        // Get existing product IDs to exclude
        $existingProductIds = $quoteBuilder->products->pluck('id')->toArray();

        $query = \App\Models\Product::with(['images'])
            ->where('status', 'active')
            ->whereNotIn('id', $existingProductIds);

        // If search is provided, search all products
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%');
            });
        } else {
            // Get category IDs from products in the quote
            $categoryIds = $quoteBuilder->products
                ->flatMap(fn($product) => $product->categories->pluck('id'))
                ->unique()
                ->toArray();

            // If there are categories, filter by them (related products)
            if (!empty($categoryIds)) {
                $query->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('id', $categoryIds);
                });
            }
        }

        $products = $query->orderBy('created_at', 'desc')
            ->limit($perPage)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    private function sendNotificationEmail(QuoteBuilder $quote, string $action)
    {
        $adminEmailSetting = Setting::where('key', 'email_notification_admin')->first();
        $adminEmail = $adminEmailSetting ? $adminEmailSetting->value : config('mail.from.address');

        if (!$adminEmail) {
            return;
        }

        $user = $quote->user;
        $userName = $user ? $user->name : 'Unknown User';
        $userEmail = $user ? $user->email : 'unknown@example.com';

        $itemCount = $quote->products->count();
        $actionVerb = $action === 'created' ? 'Created' : 'Updated';

        $emailBody = implode("\n", [
            "Quote Builder {$actionVerb}",
            "========================",
            "",
            "Quote Name   : " . $quote->name,
            "Customer     : " . $userName . " (" . $userEmail . ")",
            "Total Items  : " . $itemCount,
            "",
            "Items in Quote:",
            "---------------",
        ]);

        foreach ($quote->products as $product) {
            $qty = $product->pivot->quantity ?? 1;
            $emailBody .= "\n - " . $product->title . " (Qty: " . $qty . ")";
        }

        $emailBody .= "\n\n-----------------";
        $emailBody .= "\nDate: " . now()->format('Y-m-d H:i:s');

        try {
            Mail::raw($emailBody, function ($m) use ($adminEmail, $userEmail, $userName, $quote, $actionVerb) {
                $m->to($adminEmail)
                    ->replyTo($userEmail, $userName)
                    ->subject("Quote {$actionVerb}: " . $quote->name);
            });
        } catch (\Throwable $e) {
            Log::error("Failed to send Quote Builder notification email: " . $e->getMessage());
        }
    }
}
