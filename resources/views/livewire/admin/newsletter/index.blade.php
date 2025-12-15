<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold" style="color: #000;">Newsletter Subscriptions</h1>
        <button wire:click="exportCsv" wire:loading.attr="disabled"
            class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition flex items-center gap-2 disabled:opacity-50">
            <!-- SVG Export icon tetap default karena tidak diminta -->
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
            class="w-full md:w-80 rounded-lg border px-3 py-2"
            style="border:1px solid #AEAEAE; color: #AEAEAE; background: #fff; placeholder-color:#D2D2D2;"
            placeholder="Search emails..."
            onfocus="this.style.borderColor='#AEAEAE'"
            onblur="this.style.borderColor='#AEAEAE'">
        <style>
            input::placeholder {
                color: #D2D2D2;
            }
        </style>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-xl border">
        <table class="min-w-full text-sm">
            <thead style="background: #fff;">
                <tr>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color:#AEAEAE;">Email</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color:#AEAEAE;">Source</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color:#AEAEAE;">Subscribed At</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color:#AEAEAE;">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($subscriptions as $subscription)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-medium" style="color:#000000;">{{ $subscription->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap" style="color:#000000;">
                            {{ $subscription->source ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap" style="color:#000000;">
                            {{ $subscription->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium space-x-2">
                            @can('newsletter-subscriptions.edit')
                                <button
                                    class="inline-flex items-center justify-center border border-[#0079C2] text-[#0079C2] bg-white px-2 py-1 rounded hover:cursor-pointer transition"
                                    title="Edit"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="16" height="16" viewBox="0 0 1200 1200"
                                         style="color:#000000; display: inline;">
                                        <path fill="currentColor"
                                              d="M0 0v1200h1200V424.292l-196.875 196.875v381.958h-806.25v-806.25h381.958L775.708 0zm1050 0l-76.831 76.831l150 150L1200 150zM936.914 113.086L497.168 552.832l150 150l439.746-439.746zM441.943 622.339c-2.225.034-4.493.195-6.738.366v142.09h142.09c0-38.708-18.492-78.039-47.314-105.542c-23.842-22.751-54.675-37.428-88.038-36.914"></path>
                                    </svg>
                                </button>
                            @endcan
                            @can('newsletter-subscriptions.delete')
                                <button wire:confirm="Are you sure you want to delete this subscription?"
                                    wire:click="delete({{ $subscription->id }})"
                                    class="inline-flex items-center justify-center border border-red-600 text-red-600 bg-white px-2 py-1 rounded hover:cursor-pointer transition"
                                    title="Delete"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="14" height="14" viewBox="0 0 12 12"
                                         style="color:#000000; display: inline;">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round" d="M2 2.5h8" stroke-width="1"></path>
                                        <path fill="currentColor" d="M2 4v7c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4zm3 5.5c0 .28-.22.5-.5.5S4 9.78 4 9.5V6c0-.28.22-.5.5-.5s.5.22.5.5zm3 0c0 .28-.22.5-.5.5S7 9.78 7 9.5V6c0-.28.22-.5.5-.5s.5.22.5.5zM8 3H4V1c0-.55.45-1 1-1h2c.55 0 1 .45 1 1z"></path>
                                    </svg>
                                </button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center" style="color: #000;">No subscriptions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $subscriptions->links() }}
    </div>
</div>
