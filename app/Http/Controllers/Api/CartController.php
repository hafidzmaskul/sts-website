<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cartItems = $request->user()->cartItems()->with('product.images')->get();

        return response()->json([
            'data' => $cartItems,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = \App\Models\Product::findOrFail($request->product_id);
        $price = $product->calculatePrice($request->user()) ?? 0;

        $cartItem = $request->user()->cartItems()->where('product_id', $request->product_id)->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->price = $price; // Update price to latest
            $cartItem->save();
        } else {
            $cartItem = $request->user()->cartItems()->create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $price,
            ]);
        }

        return response()->json([
            'message' => 'Product added to cart successfully',
            'data' => $cartItem->fresh()->load('product'),
        ]);
    }

    public function decrease(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = $request->user()->cartItems()->where('product_id', $request->product_id)->firstOrFail();

        if ($cartItem->quantity > $request->quantity) {
            $cartItem->decrement('quantity', $request->quantity);
        } else {
            $cartItem->delete();

            return response()->json([
                'message' => 'Product removed from cart successfully',
            ]);
        }

        return response()->json([
            'message' => 'Product quantity decreased successfully',
            'data' => $cartItem->fresh()->load('product'),
        ]);
    }

    public function destroy(Request $request, $productId)
    {
        $request->user()->cartItems()->where('product_id', $productId)->delete();

        return response()->json([
            'message' => 'Product removed from cart successfully',
        ]);
    }
}
