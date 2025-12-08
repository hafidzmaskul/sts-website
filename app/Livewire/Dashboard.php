<?php

namespace App\Livewire;

use App\Models\Banner;
use App\Models\News;
use App\Models\NewsletterSubscription;
use App\Models\ProductCategory;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard', [
            'stats' => [
                'news' => News::count(),
                'news_published' => News::where('status', 'published')->count(),
                'newsletter' => NewsletterSubscription::count(),
                'product_categories' => ProductCategory::count(),
                'banners' => Banner::count(),
            ],
            'recent_news' => News::latest()->take(5)->get(),
            'recent_subscriptions' => NewsletterSubscription::latest()->take(5)->get(),
        ])->title('Dashboard');
    }
}
