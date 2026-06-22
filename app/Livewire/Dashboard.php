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

        // Initialize variables to avoid undefined variable errors in view
        $recentTransactions = collect();
        $currentBalance = 0;

        if (in_array($userRole, $fixedRoles)) {
            $currentBalance = 0;
            // $recentTransactions initialized above

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
            ])->title($userRole ? 'Hi '.ucfirst($userRole) : 'Hi Dashboard');
        }

        // Initialize default values for the view
        $businessStats = [
            'total_revenue' => 0,
            'total_orders' => 0,
            'total_customers' => 0,
        ];
        $chartData = [
            'labels' => [],
            'revenue' => [],
            'orders' => [],
        ];

        // Only calculate business stats for Admin/Staff who see the full dashboard
        if (! in_array($userRole, $fixedRoles)) {
            // 1. Business Stats
            $revenueStatuses = ['paid', 'processing', 'left the storage', 'in transit', 'delivered'];

            $businessStats['total_revenue'] = \App\Models\Transaction::whereIn('status', $revenueStatuses)->sum('total_amount');
            $businessStats['total_orders'] = \App\Models\Transaction::where('status', '!=', 'cancelled')->count();
            $businessStats['total_customers'] = \App\Models\User::role(['customer', 'trade account', 'credit facilities account'])->count(); // Broad definition of customer

            // 2. Chart Data (Last 30 Days)
            $endDate = now()->endOfDay();
            $startDate = now()->subDays(29)->startOfDay();

            $period = \Carbon\CarbonPeriod::create($startDate, $endDate);

            $dailyRevenue = \App\Models\Transaction::whereIn('status', $revenueStatuses)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
                ->groupByRaw('DATE(created_at)')
                ->pluck('total', 'date');

            $dailyOrders = \App\Models\Transaction::where('status', '!=', 'cancelled')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupByRaw('DATE(created_at)')
                ->pluck('count', 'date');

            foreach ($period as $date) {
                $formattedDate = $date->format('Y-m-d');
                $displayDate = $date->format('M j');

                $chartData['labels'][] = $displayDate;
                $chartData['revenue'][] = $dailyRevenue->get($formattedDate, 0);
                $chartData['orders'][] = $dailyOrders->get($formattedDate, 0);
            }
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
            'businessStats' => $businessStats,
            'chartData' => $chartData,
            'recent_news' => News::latest()->take(5)->get(),
            'recent_subscriptions' => NewsletterSubscription::latest()->take(5)->get(),
        ])->title($userRole ? 'Hi '.ucfirst($userRole) : 'Hi Dashboard');
    }
}
