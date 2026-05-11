<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-black">Create Pricing Formula</h1>
        <a href="{{ route('admin.pricing-formulas.index') }}" wire:navigate class="text-[#0079C2] hover:text-blue-700 transition">
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form wire:submit="save" class="space-y-5">
            <div class="w-full">
                <label class="block text-sm font-medium mb-1 text-black">Label</label>
                <input type="text" wire:model="label" placeholder="e.g., Seasonal Discount"
                    class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2] focus:outline-none focus:border-[#0079C2] bg-white">
                @error('label') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="w-full">
                    <label class="block text-sm font-medium mb-1 text-black">Margin (%)</label>
                    <input type="number" wire:model="margin" step="0.01" placeholder="e.g. 15.00" max="99.99"
                        class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2] focus:outline-none focus:border-[#0079C2] bg-white">
                    @error('margin') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="w-full">
                    <label class="block text-sm font-medium mb-1 text-black">Markup (%)</label>
                    <input type="number" wire:model="markup" step="0.01" placeholder="e.g. 20.00"
                        class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2] focus:outline-none focus:border-[#0079C2] bg-white">
                    @error('markup') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="w-full">
                    <label class="block text-sm font-medium mb-1 text-black">Discount (%)</label>
                    <input type="number" wire:model="discount" step="0.01" placeholder="e.g. 10.00" max="100"
                        class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2] focus:outline-none focus:border-[#0079C2] bg-white">
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
                <button type="submit"
                    class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                    Create Formula
                </button>
            </div>
        </form>
    </div>
</div>
