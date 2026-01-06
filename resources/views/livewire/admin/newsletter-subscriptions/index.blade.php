<div>
    <header
        class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold text-black">
                Newsletter Subscriptions
            </h1>
        </div>
    </header>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-12 sm:px-6 lg:px-8">

        <div class="flex items-center gap-3">
            <input type="text" wire:model.live="search" placeholder="Search by email..."
                class="w-full md:w-80 rounded-lg border px-3 py-2 bg-white text-black border-zinc-200" />
            <button wire:click="exportCsv"
                class="flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white hover:bg-zinc-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Download CSV
            </button>
        </div>

        <div class="overflow-x-auto rounded-xl border border-zinc-200 bg-white">
            <table class="min-w-full text-sm text-black">
                <thead class="bg-zinc-50 text-left text-black">
                    <tr>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Subscribed At</th>
                        <th class="px-4 py-3 w-40">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $subscription)
                        <tr class="border-t border-zinc-200">
                            <td class="px-4 py-3 font-medium text-black">{{ $subscription->email }}</td>
                            <td class="px-4 py-3">
                                @if($subscription->is_subscribe)
                                    <span
                                        class="px-2 py-0.5 rounded border text-xs text-green-700 border-green-400 bg-green-100">
                                        Subscribed
                                    </span>
                                @else
                                    <span
                                        class="px-2 py-0.5 rounded border text-xs text-zinc-600 border-zinc-400 bg-zinc-100">
                                        Unsubscribed
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-zinc-700">{{ $subscription->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    @can('newsletter-subscriptions.delete')
                                        <button wire:click="delete({{ $subscription->id }})"
                                            wire:confirm="Are you sure you want to delete this subscription?"
                                            class="px-3 py-1.5 rounded border text-red-600 border-zinc-400 bg-white">Delete</button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-zinc-500">No subscriptions
                                found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $subscriptions->links() }}</div>

    </div>
</div>
