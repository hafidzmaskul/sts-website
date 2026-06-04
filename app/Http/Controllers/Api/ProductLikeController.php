<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductLikeController extends Controller
{
    /**
     * Get the list of products liked by the authenticated user.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $perPage = $request->input('per_page', 10);
        $products = $user->likedProducts()
            ->with(['brand', 'categories', 'images'])
            ->orderByPivot('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * Like a product.
     */
    public function like(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->input('product_id');
        $user = $request->user();

        if (! $user->likedProducts()->where('product_id', $productId)->exists()) {
            $user->likedProducts()->attach($productId);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product liked successfully',
            'data' => [
                'liked' => true,
            ],
        ]);
    }

    /**
     * Unlike a product.
     */
    public function unlike(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->input('product_id');
        $user = $request->user();

        if ($user->likedProducts()->where('product_id', $productId)->exists()) {
            $user->likedProducts()->detach($productId);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product unliked successfully',
            'data' => [
                'liked' => false,
            ],
        ]);
    }
}
