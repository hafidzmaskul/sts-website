<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class PageController
{
    public function services(): Response
    {
        return Inertia::render('Service');
    }

    public function serviceDetail(string $slug): Response
    {
        return Inertia::render('ServiceDetail', [
            'slug' => $slug,
        ]);
    }

    public function about(): Response
    {
        return Inertia::render('AboutUs');
    }

    public function contact(): Response
    {
        return Inertia::render('ContactUs');
    }

    public function news(): Response
    {
        return Inertia::render('News');
    }

    public function newsDetail(string $slug): Response
    {
        return Inertia::render('NewsDetail', [
            'slug' => $slug,
        ]);
    }

    public function companyHandbook(): Response
    {
        return Inertia::render('CompanyHandbook');
    }

    public function products(): Response
    {
        return Inertia::render('Products');
    }

    public function productDetail(string $slug): Response
    {
        return Inertia::render('ProductDetail', [
            'slug' => $slug,
        ]);
    }

    public function payment(): Response
    {
        return Inertia::render('Payment');
    }
}
