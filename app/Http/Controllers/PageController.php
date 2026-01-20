<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\News;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\QuoteBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

use function Livewire\Volt\with;

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
        $categories = ProductCategory::withCount('products')
            ->with('products.images')
            ->orderByDesc('products_count')
            ->get();


        $brand =  Brand::get(); //is_active
        $user = Auth::user()?->load('likedProducts');
        return Inertia::render('Landing', [
            'banners' => $banners,
            'featured' => $featured,
            'categories' => $categories,
            'brand' => $brand,
            'user' => $user
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
            ->with('categories')
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->get();

        $categories = \App\Models\NewsCategory::all();

        return Inertia::render('News', [
            'news' => $news,
            'categories' => $categories
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
        $products =  Product::with('images')->with('categories')->get();
        $baseProducts =  Product::with('images')->get();
        $productCategory = ProductCategory::with('children')->whereNull('parent_id')->get();
        // $categories =
        return Inertia::render('Products', [
            'products' => $products,
            'baseProducts' => $baseProducts,
            'productCategory' => $productCategory,


        ]);
    }

    public function productDetail(string $slug): Response
    {
        $product = Product::with('images')->where('slug', $slug)->with('categories')->with('brand')->firstOrFail();
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

    public function becomeCustomer()
    {
        return Inertia::render('BecomeCustomer');
    }


    public function signUpCustomer()
    {
        return Inertia::render('SignUpCustomer');
    }
    public function signUpFacility()
    {
        return Inertia::render('SignUpCrediFacilities');
    }
    public function signUp()
    {
        return Inertia::render('SignUp');
    }
    public function contactUs()
    {
        return Inertia::render('ContactUs');
    }
    public function training()
    {
        return Inertia::render('Training');
    }
    public function commisioning()
    {
        return Inertia::render('Commisioning');
    }
    public function systemDesign()
    {
        return Inertia::render('SystemDesign');
    }
    public function login()
    {
        return Inertia::render('Login');
    }

    public function quoteBuilder()
    {

        return Inertia::render('QuoteBuilder');
    }
    public function invoice()
    {
        return Inertia::render('Invoice');
    }


    public function checkout()
    {
        $auth = Auth::user()->load(['customer.company', 'roles']);
        return Inertia::render('Checkout', [
            'auth' => $auth
        ]);
    }

    public function search(\Illuminate\Http\Request $request): Response
    {
        $query = $request->input('q');
        $products = [];

        if ($query) {
            $products = Product::with(['images', 'categories', 'brand'])
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhereHas('brand', function ($subQ) use ($query) {
                            $subQ->where('name', 'like', "%{$query}%");
                        });
                })
                ->get();
        }

        return Inertia::render('SearchResults', [
            'products' => $products,
            'query' => $query
        ]);
    }
}
