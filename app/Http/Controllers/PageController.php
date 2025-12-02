<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Career;
use App\Models\News;
use App\Models\Product;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
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
        $service = Service::where('slug', $slug)
            ->with('products')
            ->firstOrFail();

        $otherServices = Service::query()
            ->where('status', true)
            ->where('id', '!=', $service->id)
            ->orderBy('sequence')
            ->orderByDesc('created_at')
            ->take(9)
            ->get();

        return Inertia::render('ServiceDetail', [
            'service' => $service,
            'otherServices' => $otherServices,
        ]);
    }

    public function about(): Response
    {
        $teamMembers = TeamMember::orderBy('sequence')->get();

        return Inertia::render('AboutUs', [
            'teamMembers' => $teamMembers,
        ]);
    }

    public function contact(): Response
    {
        return Inertia::render('ContactUs');
    }

    public function career(): Response
    {
        $jobs = Career::query()
            ->where('status', true)
            ->orderBy('sequence')
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('Career', [
            'jobs' => $jobs,
        ]);
    }

    public function careerDetail(string $slug): Response
    {
        $job = Career::query()
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $jobs = Career::query()
            ->where('status', true)
            ->where('id', '!=', $job->id)
            ->orderBy('sequence')
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        return Inertia::render('CareerDetail', [
            'job' => $job,
            'jobs' => $jobs,
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
        $product = Product::where('slug', $slug)
            ->with('services')
            ->firstOrFail();

        $products = Product::query()
            ->where('status', true)
            ->where('id', '!=', $product->id)
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        return Inertia::render('ProductDetail', [
            'product' => $product,
            'products' => $products,
        ]);
    }

    public function payment(string $slug): Response
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();
        $vat = Setting::where('key', 'vat_percentage')->first();

        return Inertia::render('Payment', [
            'product' => $product,
            'vat' => $vat,
        ]);
    }
}
