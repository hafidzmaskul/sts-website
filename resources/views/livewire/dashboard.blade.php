@if(isset($simpleView) && $simpleView)
    <div class="p-6">
        <h1 class="text-2xl font-bold text-black">Hello {{ $userName }}</h1>
        <p class="text-lg text-gray-700">Role: <span class="font-medium">{{ ucfirst($userRole) }}</span></p>



        @if(in_array($userRole, ['trade account', 'credit facilities account']) && !empty($recentTransactions))
            <div class="mt-8">
                <h2 class="text-xl font-bold text-black mb-4">Recent Transactions</h2>
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
        <h1 class="text-2xl font-bold" style="color: #000;">Dashboard</h1>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- News Stats -->
            <div class="p-6 rounded-2xl shadow border border-zinc-200">
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
            <div class="p-6 rounded-2xl shadow border border-zinc-200">
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
            <div class="p-6 rounded-2xl shadow border border-zinc-200">
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
            <div class="p-6 rounded-2xl shadow border border-zinc-200">
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

        <!-- Recent Activity Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent News -->
            <div class="p-6 rounded-2xl shadow border border-zinc-200">
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
            <div class="p-6 rounded-2xl shadow border border-zinc-200">
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