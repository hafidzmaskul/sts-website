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
        $quoteBuilders = $request->user()->quoteBuilders()->with('products')->get();

        return response()->json([
            'data' => $quoteBuilders,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_id' => 'required|array',
            'product_id.*' => 'exists:products,id',
        ]);

        $quoteBuilder = DB::transaction(function () use ($request) {
            $quoteBuilder = QuoteBuilder::create([
                'user_id' => $request->user()->id,
                'name' => $request->name,
            ]);

            $quoteBuilder->products()->attach($request->product_id);

            return $quoteBuilder;
        });

        return response()->json([
            'message' => 'Quote Builder created successfully',
            'data' => $quoteBuilder->load('products'),
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
            'data' => $quoteBuilder->load('products'),
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
        ]);

        $quoteBuilder = $request->user()->quoteBuilders()->findOrFail($id);
        $quoteBuilder->products()->syncWithoutDetaching($request->product_id);

        return response()->json([
            'message' => 'Product added to Quote Builder successfully',
            'data' => $quoteBuilder->load('products'),
        ]);
    }

    public function removeProduct(Request $request, $id, $productId)
    {
        $quoteBuilder = $request->user()->quoteBuilders()->findOrFail($id);
        $quoteBuilder->products()->detach($productId);

        return response()->json([
            'message' => 'Product removed from Quote Builder successfully',
            'data' => $quoteBuilder->load('products'),
        ]);
    }
}
