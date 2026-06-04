<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-black">Edit Pricing Formula</h1>
        <a href="{{ route('admin.pricing-formulas.index') }}" wire:navigate class="text-[#0079C2] hover:text-blue-700 transition">
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form wire:submit="save" class="space-y-5">
            <div class="w-full">
                <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">Label</label>
                <input type="text" wire:model="label" placeholder="e.g., Seasonal Discount"
                    class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2] focus:outline-none focus:border-[#0079C2]">
                @error('label') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="w-full">
                    <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">Margin (%)</label>
                    <input type="number" wire:model.live="margin" step="0.01" placeholder="e.g. 15.00" max="99.99"
                        @disabled(!empty($markup))
                        class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2] focus:outline-none focus:border-[#0079C2] bg-white disabled:bg-gray-100 disabled:opacity-50">
                    @error('margin') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="w-full">
                    <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">Markup (%)</label>
                    <input type="number" wire:model.live="markup" step="0.01" placeholder="e.g. 20.00"
                        @disabled(!empty($margin))
                        class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2] focus:outline-none focus:border-[#0079C2] bg-white disabled:bg-gray-100 disabled:opacity-50">
                    @error('markup') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="w-full">
                    <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">Discount (%)</label>
                    <input type="number" wire:model="discount" step="0.01" placeholder="e.g. 10.00" max="100"
                        class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2] focus:outline-none focus:border-[#0079C2]">
                    @error('discount') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="w-full">
                <label class="block text-sm font-medium mb-2 text-black">Apply to Brands</label>
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4 border border-[#D2D2D2] rounded-lg max-h-64 overflow-y-auto bg-white">
                    @forelse ($brands as $brand)
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" wire:model="brand_ids" value="{{ $brand->id }}"
                                class="rounded border-gray-300 text-[#0079C2] shadow-sm focus:ring-[#0079C2]">
                            <span class="text-sm text-gray-700">{{ $brand->name }}</span>
                        </label>
                    @empty
                        <p class="text-sm text-gray-500 italic col-span-full">No active brands available.</p>
                    @endforelse
                </div>
                @error('brand_ids') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <a href="{{ route('admin.pricing-formulas.index') }}" wire:navigate
                    class="px-4 py-2 rounded-lg border border-[#0079C2] text-[#0079C2] bg-transparent transition hover:bg-blue-50">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Update Formula
        </button>
            </div>
        </form>
    </div>

    <div class="mt-8">
        <h2 class="text-lg font-semibold mb-4 text-black">History</h2>
        <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-black uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-black uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-black uppercase tracking-wider">Label</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-black uppercase tracking-wider">Margin</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-black uppercase tracking-wider">Markup</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-black uppercase tracking-wider">Discount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($pricingFormula->histories()->with('user')->latest()->get() as $history)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $history->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                <div class="flex items-center gap-2">
                                    @if($history->user)
                                        <img src="{{ $history->user->profile_photo_url }}" class="w-6 h-6 rounded-full" alt="{{ $history->user->name }}">
                                        <span class="text-sm font-medium text-black">{{ $history->user->name }}</span>
                                    @else
                                        <span class="text-sm text-black">System/Unknown</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-black">
                                {{ $history->label }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $history->margin ?? '-' }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $history->markup ?? '-' }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                {{ $history->discount ?? '-' }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
