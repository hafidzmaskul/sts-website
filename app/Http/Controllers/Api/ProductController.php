<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'categories', 'images', 'variants.images'])
            ->where('status', 'active');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('sku', 'like', '%'.$search.'%');
            });
        }

        if ($request->has('category')) {
            $category = $request->category;
            $query->whereHas('categories', function ($q) use ($category) {
                $q->where('name', 'like', '%'.$category.'%')
                    ->orWhere('slug', 'like', '%'.$category.'%');
            });
        }

        $perPage = $request->input('per_page', 10);
        $products = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    public function show($slug)
    {
        $product = Product::with(['brand', 'categories', 'images', 'attachments', 'variants.images', 'variants.attachments'])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        $variants = collect();

        if ($product->parent_id) {
            $parent = Product::with(['variants.images', 'variants.attachments', 'images', 'attachments', 'categories', 'brand'])
                ->where('status', 'active')
                ->find($product->parent_id);
            if ($parent) {
                $variants = collect([$parent])->concat($parent->variants);
            }
        } elseif ($product->variants->isNotEmpty()) {
            $variants = collect([$product])->concat($product->variants);
        }

        $product->setRelation('variants', $variants->values());

        $relatedProducts = Product::with(['brand', 'categories', 'images'])
            ->where('status', 'active')
            ->where('id', '!=', $product->id)
            ->whereHas('categories', function ($query) use ($product) {
                $query->whereIn('id', $product->categories->pluck('id'));
            })
            ->limit(4)
            ->get();

        $product->setAttribute('related_products', $relatedProducts);

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }
}
