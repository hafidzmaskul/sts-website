<div class="p-6 space-y-6 dark:bg-zinc-900">
    <div class="flex justify-between items-center bg-transparent">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Newsletter Subscriptions</h1>
        <button wire:click="exportCsv" wire:loading.attr="disabled"
            class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            <span wire:loading.remove wire:target="exportCsv">Export CSV</span>
            <span wire:loading wire:target="exportCsv">Exporting...</span>
        </button>
    </div>

    <!-- Search -->
    <div class="flex items-center gap-3">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search emails..."
            class="w-full md:w-80 rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-xl border dark:border-zinc-700 dark:bg-zinc-800">
        <table class="min-w-full text-sm">
            <thead class="bg-zinc-50 text-left dark:bg-zinc-900 dark:text-zinc-200">
                <tr>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider">Source</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider">Subscribed At</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse($subscriptions as $subscription)
                    <tr class="dark:text-zinc-100">
                        <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $subscription->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400">
                            {{ $subscription->source ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400">
                            {{ $subscription->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium space-x-2">
                            @can('newsletter-subscriptions.delete')
                                <button wire:confirm="Are you sure you want to delete this subscription?"
                                    wire:click="delete({{ $subscription->id }})"
                                    class="px-3 py-1.5 rounded border text-red-600 dark:border-zinc-600 dark:bg-zinc-800 dark:text-red-400 hover:bg-red-50 dark:hover:bg-zinc-700">Delete</button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-zinc-400">No subscriptions
                            found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $subscriptions->links() }}
    </div>
</div>