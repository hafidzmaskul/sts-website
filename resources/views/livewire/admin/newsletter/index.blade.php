<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold" style="color: #000;">Newsletter Subscriptions</h1>
        <button wire:click="exportCsv" wire:loading.attr="disabled"
            class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition flex items-center gap-2 disabled:opacity-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            <span wire:loading.remove wire:target="exportCsv">Export CSV</span>
            <span wire:loading wire:target="exportCsv">Exporting...</span>
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Card header with search -->
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <div class="relative w-full md:w-80">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
                    </svg>
                </span>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search emails..."
                    class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-black placeholder-gray-400 transition"
                >
            </div>
        </div>

        <!-- Table container -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y">
                <!-- Table header -->
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-black text-left">Email</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-black text-left">Source</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-black text-left">Subscribed At</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-black text-right">Actions</th>
                    </tr>
                </thead>
                <!-- Table body -->
                <tbody class="divide-y divide-gray-100">
                    @forelse($subscriptions as $subscription)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-black">{{ $subscription->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($subscription->source)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $subscription->source }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-black">
                                        -
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-black">{{ $subscription->created_at->format('M d, Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
<div class="flex items-center justify-end gap-2">
<div class="flex justify-end gap-2">
                                    @can('newsletter-subscriptions.edit')
                                        <button
                                            title="Edit"
                                            class="inline-flex items-center justify-center p-1 rounded hover:bg-blue-50 text-blue-600 transition"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="w-5 h-5"
                                                 fill="none" viewBox="0 0 24 24"
                                                 stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13.5V19h5.5l6.364-6.364a2 2 0 000-2.828l-5.672-5.672a2 2 0 00-2.828 0L3 13.5V19h5.5l6.364-6.364z"/>
                                            </svg>
                                        </button>
                                    @endcan
                                    @can('newsletter-subscriptions.delete')
                                        <button wire:confirm="Are you sure you want to delete this subscription?" wire:click="delete({{ $subscription->id }})" class="text-black hover:text-red-600 transition-colors inline-flex" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                    @endcan
                                </div>
</div>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <rect width="20" height="14" x="2" y="5" rx="2" fill="none" stroke="currentColor"/>
                                        <path stroke-linecap="round" d="M2 5l10 7l10-7"/>
                                    </svg>
                                    <div class="text-lg font-semibold text-black mb-1">No subscriptions found.</div>
                                    <div class="text-sm text-black">Try adjusting your search or filters to find results.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 overflow-x-auto">
            {{ $subscriptions->links() }}
        </div>
    </div>
</div>
