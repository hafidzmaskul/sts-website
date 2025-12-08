<div class="p-6 space-y-6 dark:bg-zinc-900">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Dashboard</h1>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- News Stats -->
        <div class="bg-white p-6 rounded-2xl shadow dark:bg-zinc-900 dark:border dark:border-zinc-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-500 text-sm font-medium dark:text-zinc-400">Total Articles</h3>
                <div class="p-2 bg-blue-50 rounded-lg dark:bg-blue-900/20">
                    <flux:icon.document-text class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                </div>
            </div>
            <div class="flex items-baseline">
                <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['news'] }}</span>
                <span class="ml-2 text-sm text-gray-500 dark:text-zinc-400">({{ $stats['news_published'] }} published)</span>
            </div>
        </div>

        <!-- Newsletter Stats -->
        <div class="bg-white p-6 rounded-2xl shadow dark:bg-zinc-900 dark:border dark:border-zinc-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-500 text-sm font-medium dark:text-zinc-400">Subscribers</h3>
                <div class="p-2 bg-green-50 rounded-lg dark:bg-green-900/20">
                    <flux:icon.envelope class="w-6 h-6 text-green-600 dark:text-green-400" />
                </div>
            </div>
            <div class="flex items-baseline">
                <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['newsletter'] }}</span>
            </div>
        </div>

        <!-- Product Categories Stats -->
        <div class="bg-white p-6 rounded-2xl shadow dark:bg-zinc-900 dark:border dark:border-zinc-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-500 text-sm font-medium dark:text-zinc-400">Categories</h3>
                <div class="p-2 bg-purple-50 rounded-lg dark:bg-purple-900/20">
                    <flux:icon.tag class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                </div>
            </div>
            <div class="flex items-baseline">
                <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['product_categories'] }}</span>
            </div>
        </div>

        <!-- Banners Stats -->
        <div class="bg-white p-6 rounded-2xl shadow dark:bg-zinc-900 dark:border dark:border-zinc-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-500 text-sm font-medium dark:text-zinc-400">Active Banners</h3>
                <div class="p-2 bg-orange-50 rounded-lg dark:bg-orange-900/20">
                    <flux:icon.photo class="w-6 h-6 text-orange-600 dark:text-orange-400" />
                </div>
            </div>
            <div class="flex items-baseline">
                <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['banners'] }}</span>
            </div>
        </div>
    </div>

    <!-- Recent Activity Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent News -->
        <div class="bg-white p-6 rounded-2xl shadow dark:bg-zinc-900 dark:border dark:border-zinc-700">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Recent Articles</h3>
            <div class="space-y-4">
                @forelse($recent_news as $article)
                    <div class="flex items-center justify-between border-b border-gray-100 dark:border-zinc-800 pb-4 last:border-0 last:pb-0">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-[200px]">{{ $article->title }}</h4>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">{{ $article->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full {{ $article->status === 'published' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ ucfirst($article->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-zinc-400">No recent articles.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Subscriptions -->
        <div class="bg-white p-6 rounded-2xl shadow dark:bg-zinc-900 dark:border dark:border-zinc-700">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">New Subscribers</h3>
            <div class="space-y-4">
                @forelse($recent_subscriptions as $sub)
                    <div class="flex items-center justify-between border-b border-gray-100 dark:border-zinc-800 pb-4 last:border-0 last:pb-0">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $sub->email }}</h4>
                            <p class="text-xs text-gray-500 dark:text-zinc-400">Source: {{ $sub->source ?? 'Unknown' }}</p>
                        </div>
                        <span class="text-xs text-gray-500 dark:text-zinc-400">
                            {{ $sub->created_at->diffForHumans() }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-zinc-400">No recent subscribers.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
