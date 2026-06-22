@if(isset($simpleView) && $simpleView)
    <div class="p-6 space-y-6">
        <!-- Premium Greeting Section -->
        <div class="bg-white rounded-2xl p-6 border border-zinc-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900">Welcome back, {{ $userName }}! 👋</h1>
                <p class="text-sm text-zinc-500 mt-1">Here is your account overview. Your role: <span class="font-semibold text-[#0079C2]">{{ ucfirst($userRole) }}</span></p>
            </div>
            <div class="text-sm text-zinc-500 bg-zinc-50 px-4 py-2 rounded-xl border border-zinc-100 self-start md:self-auto font-medium">
                {{ now()->format('l, j F Y') }}
            </div>
        </div>

        @if($userRole === 'credit facilities account' && isset($currentBalance))
            <div class="mt-6 mb-8">
                <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 sm:w-1/3">
                    <div class="px-4 py-5 sm:p-6 flex justify-between items-center">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Current Credit Balance
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                £{{ number_format($currentBalance, 2) }}
                            </dd>
                        </div>
                        <a href="{{ route('dashboard.credit-limits.index') }}"
                            class="px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50 font-medium">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @endif



        @if(in_array($userRole, ['trade account', 'credit facilities account']) && !empty($recentTransactions))
            <div class="mt-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-black">Recent Transactions</h2>
                    <a href="{{ route('dashboard.transactions.index') }}"
                        class="px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50 font-medium">
                        View All
                    </a>
                </div>
                <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
                    <ul role="list" class="divide-y divide-gray-200">
                        @forelse($recentTransactions as $transaction)
                            <li>
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-[#0079C2] truncate">
                                            {{ $transaction->invoice_code }}
                                        </p>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <p
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ ucfirst($transaction->status) }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                Amount: ${{ number_format($transaction->total_amount, 2) }}
                                            </p>
                                        </div>
                                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                            <p>
                                                {{ $transaction->created_at->format('M j, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <p class="p-4 text-sm text-gray-500">No transactions found.</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        @endif
    </div>
@else
    <div class="p-6 space-y-6">
        <!-- Premium Greeting Section -->
        <div class="bg-white rounded-2xl p-6 border border-zinc-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                <p class="text-sm text-zinc-500 mt-1">Here is the overview of your platform for today. Your role: <span class="font-semibold text-[#0079C2]">{{ ucfirst(auth()->user()->getRoleNames()->first() ?? 'User') }}</span></p>
            </div>
            <div class="text-sm text-zinc-500 bg-zinc-50 px-4 py-2 rounded-xl border border-zinc-100 self-start md:self-auto font-medium">
                {{ now()->format('l, j F Y') }}
            </div>
        </div>

        <!-- Business Overview -->
        <h2 class="text-xl font-bold mb-4" style="color: #000;">Business Overview</h2>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Revenue -->
            <div class="p-6 rounded-2xl shadow border border-zinc-200 bg-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Revenue</h3>
                    <div class="p-2 bg-green-50 rounded-lg">
                        <flux:icon.banknotes class="w-6 h-6 text-green-600" />
                    </div>
                </div>
                <div class="flex items-baseline">
                    <span
                        class="text-3xl font-bold text-black">£{{ number_format($businessStats['total_revenue'], 2) }}</span>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="p-6 rounded-2xl shadow border border-zinc-200 bg-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Orders</h3>
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <flux:icon.shopping-bag class="w-6 h-6 text-blue-600" />
                    </div>
                </div>
                <div class="flex items-baseline">
                    <span class="text-3xl font-bold text-black">{{ number_format($businessStats['total_orders']) }}</span>
                </div>
            </div>

            <!-- Total Customers -->
            <div class="p-6 rounded-2xl shadow border border-zinc-200 bg-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Customers</h3>
                    <div class="p-2 bg-purple-50 rounded-lg">
                        <flux:icon.users class="w-6 h-6 text-purple-600" />
                    </div>
                </div>
                <div class="flex items-baseline">
                    <span
                        class="text-3xl font-bold text-black">{{ number_format($businessStats['total_customers']) }}</span>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="p-6 rounded-2xl shadow border border-zinc-200 bg-white mb-8">
            <h3 class="text-lg font-semibold mb-6 text-black">Revenue & Order Trends (Last 30 Days)</h3>
            <div class="relative h-80 w-full">
                <canvas id="dashboardChart"></canvas>
            </div>
        </div>

        <h2 class="text-xl font-bold mb-4" style="color: #000;">Content Stats</h2>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- News Stats -->
            <div class="p-6 rounded-2xl shadow border border-zinc-200 bg-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium" style="color: #000;">Total Articles</h3>
                    <div class="p-2 rounded-lg">
                        <flux:icon.document-text class="w-6 h-6" style="color: #0079C2;" />
                    </div>
                </div>
                <div class="flex items-baseline">
                    <span class="text-3xl font-bold text-black">{{ $stats['news'] }}</span>
                    <span class="ml-2 text-sm" style="color: #000;">({{ $stats['news_published'] }} published)</span>
                </div>
            </div>

            <!-- Newsletter Stats -->
            <div class="p-6 rounded-2xl shadow border border-zinc-200 bg-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium" style="color: #000;">Subscribers</h3>
                    <div class="p-2 rounded-lg">
                        <flux:icon.envelope class="w-6 h-6" style="color: #69C587;" />
                    </div>
                </div>
                <div class="flex items-baseline">
                    <span class="text-3xl font-bold text-black">{{ $stats['newsletter'] }}</span>
                </div>
            </div>

            <!-- Product Categories Stats -->
            <div class="p-6 rounded-2xl shadow border border-zinc-200 bg-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium" style="color: #000;">Categories</h3>
                    <div class="p-2 rounded-lg">
                        <flux:icon.tag class="w-6 h-6" style="color: #9F5DD9;" />
                    </div>
                </div>
                <div class="flex items-baseline">
                    <span class="text-3xl font-bold text-black">{{ $stats['product_categories'] }}</span>
                </div>
            </div>

            <!-- Banners Stats -->
            <div class="p-6 rounded-2xl shadow border border-zinc-200 bg-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium" style="color: #000;">Active Banners</h3>
                    <div class="p-2 rounded-lg">
                        <flux:icon.photo class="w-6 h-6" style="color: #FF9E0B;" />
                    </div>
                </div>
                <div class="flex items-baseline">
                    <span class="text-3xl font-bold text-black">{{ $stats['banners'] }}</span>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('livewire:navigated', function () {
                initDashboardChart();
            });

            // Initial load in case not navigated via Livewire SPA
            document.addEventListener('DOMContentLoaded', function () {
                initDashboardChart();
            });

            function initDashboardChart() {
                const ctx = document.getElementById('dashboardChart');
                if (!ctx) return;

                // Destroy existing chart if it exists to prevent duplicates
                if (window.myDashboardChart) {
                    window.myDashboardChart.destroy();
                }

                const chartData = @json($chartData);

                window.myDashboardChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                label: 'Revenue (£)',
                                data: chartData.revenue,
                                borderColor: '#0ea5e9', // Sky 500
                                backgroundColor: 'rgba(14, 165, 233, 0.1)',
                                borderWidth: 2,
                                yAxisID: 'y',
                                tension: 0.3,
                                fill: true
                            },
                            {
                                label: 'Orders',
                                data: chartData.orders,
                                borderColor: '#8b5cf6', // Violet 500
                                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                                borderWidth: 2,
                                borderDash: [5, 5],
                                yAxisID: 'y1',
                                tension: 0.3,
                                fill: false
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                        },
                        scales: {
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'Revenue (£)'
                                },
                                ticks: {
                                    callback: function  (value) {                                     return '£' + value;                                 }                             }                         },                         y1: {                             type: 'linear',                             display: true,                             position: 'right',                             title: {                                 display: true,                                 text: 'Orders'                             },                             grid: {                                 drawOnChartArea: false,                             },                             beginAtZero: true,                             ticks: {                                 stepSize: 1                             }                         },                     }                 }             });         }
        </script>

        <!-- Recent Activity Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent News -->
            <div class="p-6 rounded-2xl shadow border border-zinc-200 bg-white">
                <h3 class="text-lg font-semibold mb-4" style="color: #000;">Recent Articles</h3>
                <div class="space-y-4">
                    @forelse($recent_news as $article)
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                            <div>
                                <h4 class="text-sm font-medium text-black truncate max-w-[200px]">{{ $article->title }}</h4>
                                <p class="text-xs" style="color: #000;">{{ $article->created_at->diffForHumans() }}</p>
                            </div>
                            <span
                                class="px-2 py-1 text-xs rounded-full border {{ $article->status === 'published' ? 'border-green-600 text-green-600' : 'border-gray-400 text-gray-500' }}"
                                style="background:transparent;">
                                {{ ucfirst($article->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm" style="color: #000;">No recent articles.</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Subscriptions -->
            <div class="p-6 rounded-2xl shadow border border-zinc-200 bg-white">
                <h3 class="text-lg font-semibold mb-4" style="color: #000;">New Subscribers</h3>
                <div class="space-y-4">
                    @forelse($recent_subscriptions as $sub)
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                            <div>
                                <h4 class="text-sm font-medium text-black">{{ $sub->email }}</h4>
                                <p class="text-xs" style="color:#AEAEAE;">Source: {{ $sub->source ?? 'Unknown' }}</p>
                            </div>
                            <span class="text-xs" style="color:#AEAEAE;">
                                {{ $sub->created_at->diffForHumans() }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm" style="color: #000;">No recent subscribers.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endif