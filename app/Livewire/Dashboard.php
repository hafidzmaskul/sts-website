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
        $user = auth()->user();
        $userRole = $user->getRoleNames()->first(); // Assuming single role mostly, or take first.

        $fixedRoles = ['guest', 'trade account', 'credit facilities account', 'child'];

        if (in_array($userRole, $fixedRoles)) {
            $currentBalance = 0;
            if (in_array($userRole, ['trade account', 'credit facilities account']) && $user->customer) {
                $recentTransactions = $user->customer->transactions()
                    ->latest()
                    ->take(5)
                    ->get();

                if ($userRole === 'credit facilities account' && $user->customer->company) {
                    $latestLimit = $user->customer->company->creditLimits()->latest()->first();
                    $currentBalance = $latestLimit ? $latestLimit->balance : 0;
                }
            }

            return view('livewire.dashboard', [
                'simpleView' => true,
                'userName' => $user->name,
                'userRole' => $userRole,
                'recentTransactions' => $recentTransactions,
                'currentBalance' => $currentBalance,
            ])->title('Dashboard');
        }

        return view('livewire.dashboard', [
            'simpleView' => false,
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
