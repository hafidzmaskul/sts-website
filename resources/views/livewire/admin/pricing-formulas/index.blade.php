<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-black">Pricing Formulas</h1>
        @can('pricing-formulas.create')
            <a href="{{ route('admin.pricing-formulas.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Create Formula
        </a>
        @endcan
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <!-- Header: Search -->
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <div class="relative max-w-full md:max-w-xs">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <!-- Search Icon SVG -->
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" wire:model.live.debounce="search" placeholder="Search formulas..."
                    class="block w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 bg-white placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition text-black" />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-black uppercase tracking-wider">Label</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-black uppercase tracking-wider">Margin</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-black uppercase tracking-wider">Markup</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-black uppercase tracking-wider">Discount</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-black uppercase tracking-wider">Created By</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-black uppercase tracking-wider">Last Updated</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($formulas as $formula)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-black">
                                {{ $formula->label }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $formula->margin ?? '-' }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $formula->markup ?? '-' }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $formula->discount ?? '-' }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                <div class="flex items-center gap-2">
                                    @if($formula->user)
                                        <img src="{{ $formula->user->profile_photo_url }}" class="w-6 h-6 rounded-full" alt="{{ $formula->user->name }}">
                                        <span class="text-sm font-medium text-black">{{ $formula->user->name }}</span>
                                    @else
                                        <span class="text-sm text-black">System</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $formula->updated_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
<div class="flex items-center justify-end gap-2">
<div class="flex justify-end gap-2">
                                    @can('pricing-formulas.edit')
                                        <a href="{{ route('admin.pricing-formulas.edit', $formula->id) }}" class="text-black hover:text-blue-600 transition-colors inline-flex" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    @endcan

                                    @can('pricing-formulas.delete')
                                        <button wire:confirm="Are you sure you want to delete this?" wire:click="delete({{ $formula->id }})" class="text-black hover:text-red-600 transition-colors inline-flex" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                    @endcan
                                </div>
</div>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-black">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor"
                                        stroke-width="1.5" viewBox="0 0 64 64">
                                        <rect x="12" y="20" width="40" height="28" rx="4" fill="currentColor" />
                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                            d="M24 28h16M24 36h10" />
                                    </svg>
                                    <div class="text-lg font-semibold text-black mb-1">No pricing formulas found</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 overflow-x-auto">
            {{ $formulas->links() }}
        </div>
    </div>
</div>
