<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\News;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class PageController
{
    public function landing(): Response
    {
        $banners = Banner::query()
            ->latest()
            ->get([
                'id',
                'name',
                'file_path',
                'cta_url',
            ]);

        $featured = Product::with(['images' => function ($query) {
            $query->orderBy('sequence');
        }])->get()->map(function ($product) {
            // Ambil image_path dari gambar dengan sequence == 1 (atau null jika tidak ada)
            $mainImage = $product->images->firstWhere('sequence', 1);
            $product->images = $mainImage ? $mainImage->image_path : null;
            return $product;
        });
        $categories = DB::table('product_categories as pc')
            ->leftJoin('product_category_product as pcp', 'pc.id', '=', 'pcp.product_category_id')
            ->select('pc.*', DB::raw('COUNT(pcp.product_id) as products_count'))
            ->groupBy('pc.id')
            ->get();

        return Inertia::render('Landing', [
            'banners' => $banners,
            'featured' => $featured,
            'categories' => $categories
        ]);
    }

    public function services(): Response
    {
        $articles = News::with('categories')->where('status', 'Published')->get();
        return Inertia::render('Service', [
            'services' => $articles,

        ]);
    }

    public function serviceDetail(string $slug): Response
    {
        return Inertia::render('ServiceDetail', [
            'service' => [
                'name' => 'Sample Service',
                'slug' => $slug,
                'description' => 'Dummy service description.',
                'products' => [],
            ],
            'otherServices' => [],
        ]);
    }

    public function about(): Response
    {
        return Inertia::render('AboutUs', [
            'teamMembers' => [],
        ]);
    }

    public function contact(): Response
    {
        return Inertia::render('ContactUs');
    }

    public function career(): Response
    {
        return Inertia::render('Career', [
            'jobs' => [],
        ]);
    }

    public function careerDetail(string $slug): Response
    {
        return Inertia::render('CareerDetail', [
            'job' => null,
            'jobs' => [],
        ]);
    }

    public function news(): Response
    {
        $news = News::query()
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('News', [
            'news' => $news,
        ]);
    }

    public function newsDetail(string $slug): Response
    {
        $news = News::where('slug', $slug)->with('categories')->first();
        $otherNews = News::take(2)->get();

        return Inertia::render('NewsDetail', [
            'news' => $news,
            'otherNews' => $otherNews,
        ]);
    }

    public function configuration(): Response
    {
        return Inertia::render('Configuration');
    }

    public function products(): Response
    {
        $products =  Product::with('images')->get();
        $baseProducts =  Product::with('images')->get();
        // $categories =
        return Inertia::render('Products', [
            'products' => $products,
            'baseProducts' => $baseProducts
        ]);
    }

    public function productDetail(string $slug): Response
    {
        $product = Product::with('images')->where('slug', $slug)->firstOrFail();
        $relatedProducts = Product::with('images')
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->limit(8)
            ->get();

        return Inertia::render('ProductDetail', [
            'product' => $product,
            'products' => $relatedProducts,
        ]);
    }

    public function cart(): Response
    {
        return Inertia::render('Cart');
    }

    public function likedProducts(): Response
    {
        return Inertia::render('LikedProducts');
    }

    public function payment(string $slug): Response
    {
        return Inertia::render('Payment', [
            'product' => null,
            'vat' => null,
        ]);
    }
}
