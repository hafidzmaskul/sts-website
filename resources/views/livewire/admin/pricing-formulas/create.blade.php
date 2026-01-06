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

            <div class="w-full">
                <label class="block text-sm font-medium mb-1 text-black">Type</label>
                <select wire:model="type" class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2] focus:outline-none focus:border-[#0079C2] bg-white">
                    <option value="">Select Type</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                    @endforeach
                </select>
                @error('type') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="w-full">
                <label class="block text-sm font-medium mb-1 text-black">Value</label>
                <input type="number" wire:model="value" step="0.01" placeholder="0.00"
                    class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2] focus:outline-none focus:border-[#0079C2] bg-white">
                @error('value') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
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
