<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuoteBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
