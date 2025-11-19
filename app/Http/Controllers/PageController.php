<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Product;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Inertia\Inertia;
use Inertia\Response;

class PageController
{
    public function landing(): Response
    {
        $testimonials = Testimonial::query()
            ->where('status', true)
            ->orderBy('sequence')
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('Landing', [
            'testimonials' => $testimonials,
        ]);
    }

    public function services(): Response
    {
        $services = Service::query()
            ->where('status', true)
            ->orderBy('sequence')
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('Service', [
            'services' => $services,
        ]);
    }

    public function serviceDetail(string $slug): Response
    {
        $service = Service::where('slug',$slug )->with('products')->firstOrFail();
        return Inertia::render('ServiceDetail', [
            'service' => $service,
        ]);
    }

    public function about(): Response
    {
        $teamMembers = TeamMember::all();

        return Inertia::render('AboutUs', [
            'teamMembers' => $teamMembers,
        ]);
    }

    public function contact(): Response
    {
        return Inertia::render('ContactUs');
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
        $news = News::where('slug', $slug)
        ->first();
        return Inertia::render('NewsDetail', [
            'news' => $news,
        ]);
    }

    public function companyHandbook(): Response
    {
        return Inertia::render('CompanyHandbook');
    }

    public function products(): Response
    {
        $products = Product::query()
            ->where('status', true)
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('Products', [
            'products' => $products,
        ]);
    }

    public function productDetail(string $slug): Response
    {
        $product = Product::where('slug', $slug)->with('services')
            ->firstOrFail();
        return Inertia::render('ProductDetail', [
            'product' => $product,
        ]);
    }

    public function payment(): Response
    {
        return Inertia::render('Payment');
    }
}
