<div class="p-6 space-y-6 w-full">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-black">Edit Brand</h1>
    </div>

    <div class="rounded-xl shadow p-6 border border-gray-200 bg-white">
        <form wire:submit="update" class="space-y-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Name -->
                <div class="col-span-1">
                    <label for="name" class="block text-sm font-medium mb-1 text-black">Name</label>
                    <input type="text" wire:model.live.debounce.500ms="name" id="name"
                        class="w-full rounded-lg border px-3 py-2 text-black" style="border: 1px solid #D2D2D2;"
                        placeholder="Brand Name">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Slug -->
                <div class="col-span-1">
                    <label for="slug" class="block text-sm font-medium mb-1 text-black">Slug</label>
                    <input type="text" wire:model="slug" id="slug" class="w-full rounded-lg border px-3 py-2 bg-gray-50 text-black"
                        style="border: 1px solid #D2D2D2;" readonly>
                    @error('slug') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Image -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium mb-1 text-black">Image</label>
                    <div class="mt-1 flex items-center space-x-4">
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}"
                                class="h-20 w-20 object-cover rounded-md border border-gray-300">
                        @elseif($existingImage)
                            <img src="{{ Storage::url($existingImage) }}"
                                class="h-20 w-20 object-cover rounded-md border border-gray-300">
                        @else
                            <div
                                class="h-20 w-20 rounded-md border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400">
                                <span>Preview</span>
                            </div>
                        @endif

                        <div class="relative">
                            <input type="file" wire:model="image"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <div wire:loading wire:target="image"
                                class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <span class="text-indigo-500 text-xs">Uploading...</span>
                            </div>
                        </div>
                    </div>
                    @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Description -->
                <div class="col-span-2">
                    <label for="description" class="block text-sm font-medium mb-1 text-black">Description</label>
                    <textarea wire:model="description" id="description" rows="3"
                        class="w-full rounded-lg border px-3 py-2 text-black"
                        style="border: 1px solid #D2D2D2;"></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Website -->
                <div class="col-span-2">
                    <label for="website" class="block text-sm font-medium mb-1 text-black">Website URL</label>
                    <input type="url" wire:model="website" id="website" class="w-full rounded-lg border px-3 py-2 text-black"
                        style="border: 1px solid #D2D2D2;" placeholder="https://example.com">
                    @error('website') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Pricing Formula -->
                <div class="col-span-2">
                    <label for="pricing_formula_id" class="block text-sm font-medium mb-1 text-black">
                        Pricing Formula
                    </label>
                    <div class="text-xs text-gray-500 mb-2">Select a pricing formula to apply automatically.</div>
                    <select wire:model="pricing_formula_id" id="pricing_formula_id"
                        class="w-full rounded-lg border px-3 py-2 text-black bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-btn-primary-ring">
                        <option value="">None</option>
                        @foreach($pricingFormulas as $formula)
                            <option value="{{ $formula->id }}">{{ $formula->label }} @if($formula->summary)({{ $formula->summary }})@endif</option>
                        @endforeach
                    </select>
                    @error('pricing_formula_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Sort Order -->
                <div class="col-span-1">
                    <label for="sort_order" class="block text-sm font-medium mb-1 text-black">Sort Order</label>
                    <input type="number" wire:model="sort_order" id="sort_order"
                        class="w-full rounded-lg border px-3 py-2 text-black" style="border: 1px solid #D2D2D2;">
                    @error('sort_order') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Active Status -->
                <div class="col-span-1 flex items-end pb-2">
                    <div class="flex items-center h-10">
                        <input wire:model="is_active" id="is_active" type="checkbox"
                            class="focus:ring-btn-primary-ring h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm font-medium text-black">
                            Active
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <a href="{{ route('admin.brands.index') }}"
                    class="mr-3 inline-flex justify-center rounded-lg border border-[#0079C2] text-[#0079C2] px-4 py-2 font-semibold hover:cursor-pointer transition w-full md:w-auto bg-white">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Update Brand
        </button>
            </div>
        </form>
    </div>
</div>
