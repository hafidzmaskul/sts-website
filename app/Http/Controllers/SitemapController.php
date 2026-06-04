<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\News;
use App\Models\Product;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/'))
            ->add(Url::create('/about-us'))
            ->add(Url::create('/services'))
            ->add(Url::create('/contact-us'))
            ->add(Url::create('/careers'))
            ->add(Url::create('/news'))
            ->add(Url::create('/products'));

        // Add Products
        Product::where('status', 'active')->get()->each(function (Product $product) use ($sitemap) {
            $sitemap->add(
                Url::create(route('products.detail', $product->slug))
                    ->setLastModificationDate($product->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8)
            );
        });

        // Add News
        News::where('status', 'published')->get()->each(function (News $news) use ($sitemap) {
            $sitemap->add(
                Url::create(route('news.detail', $news->slug))
                    ->setLastModificationDate($news->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.7)
            );
        });

        // Add Careers
        Career::where('is_active', true)->get()->each(function (Career $career) use ($sitemap) {
            $sitemap->add(
                Url::create(route('careers.detail', $career->slug))
                    ->setLastModificationDate($career->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.6)
            );
        });

        return $sitemap->toResponse(request());
    }
}
