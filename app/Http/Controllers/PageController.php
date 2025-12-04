<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\News;
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

        return Inertia::render('Landing', [
            'banners' => $banners,
        ]);
    }

    public function services(): Response
    {
        return Inertia::render('Service', [
            'services' => [],
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
        $products = [
            [
                'id' => 1,
                'name' => 'Smart CCTV Camera Pro',
                'slug' => 'smart-cctv-camera-pro',
                'price' => 'Rp 1.250.000',
            ],
            [
                'id' => 2,
                'name' => 'Access Control Door Lock',
                'slug' => 'access-control-door-lock',
                'price' => 'Rp 1.850.000',
            ],
            [
                'id' => 3,
                'name' => 'Network Switch 24 Port',
                'slug' => 'network-switch-24-port',
                'price' => 'Rp 2.150.000',
            ],
            [
                'id' => 4,
                'name' => 'Smart Office Starter Kit',
                'slug' => 'smart-office-starter-kit',
                'price' => 'Rp 3.500.000',
            ],
            [
                'id' => 5,
                'name' => 'Smart Office Starter Kit',
                'slug' => 'smart-office-starter-kit',
                'price' => 'Rp 3.500.000',
            ],
            [
                'id' => 6,
                'name' => 'Smart Office Starter Kit',
                'slug' => 'smart-office-starter-kit',
                'price' => 'Rp 3.500.000',
            ],
            [
                'id' => 7,
                'name' => 'Smart Office Starter Kit',
                'slug' => 'smart-office-starter-kit',
                'price' => 'Rp 3.500.000',
            ],
            [
                'id' => 8,
                'name' => 'Smart Office Starter Kit',
                'slug' => 'smart-office-starter-kit',
                'price' => 'Rp 3.500.000',
            ],
        ];

        return Inertia::render('Products', [
            'products' => $products,
        ]);
    }

    public function productDetail(string $slug): Response
    {
        return Inertia::render('ProductDetail', [
            'product' => [
                'id' => 1,
                'name' => 'Smart CCTV Camera Pro',
                'slug' => $slug,
                'description' => 'Dummy product description.',
                'price' => 'Rp 1.250.000',
            ],
            'products' => [],
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
