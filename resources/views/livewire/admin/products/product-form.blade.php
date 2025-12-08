<div class="space-y-6 max-h-[70vh] overflow-y-auto px-1">
    <!-- Basic Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Brand Name</label>
                <input type="text" wire:model="brand_name"
                    class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                @error('brand_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Title</label>
                <input type="text" wire:model.live="title"
                    class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Slug</label>
                <input type="text" wire:model="slug"
                    class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900">
                @error('slug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Base Price (GBP)</label>
                <input type="number" step="0.01" wire:model="base_price"
                    class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                @error('base_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Status</label>
                <select wire:model="status"
                    class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="space-y-2 pt-4">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" wire:model="is_sign_up_for_pricing"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-zinc-800 dark:border-zinc-700 dark:accent-zinc-700">
                    <span class="text-sm text-gray-700 dark:text-zinc-100">Sign Up For Pricing Only</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" wire:model="is_exclusive"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-zinc-800 dark:border-zinc-700 dark:accent-zinc-700">
                    <span class="text-sm text-gray-700 dark:text-zinc-100">Exclusive Product</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-2">Categories</label>
        <div
            class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-40 overflow-y-auto border rounded-lg p-3 dark:bg-zinc-800 dark:border-zinc-700">
            @foreach($categories as $category)
                <label class="flex items-center gap-2 dark:text-zinc-100">
                    <input type="checkbox" wire:model="selectedCategories" value="{{ $category->id }}"
                        class="dark:accent-zinc-700">
                    <span class="text-sm">{{ $category->name }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <!-- Rich Text Fields -->
    <div class="space-y-6">
        <div wire:ignore x-data x-init="$nextTick(() => window.initCKEditor('key_feature_editor', 'key_feature_input', 'key_feature'))">
            <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Key Features</label>
            <div id="key_feature_editor"
                class="min-h-[200px] rounded-lg border border-input bg-background dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
            </div>
            <input type="hidden" id="key_feature_input" wire:model="key_feature">
        </div>

        <div wire:ignore x-data x-init="$nextTick(() => window.initCKEditor('product_overview_editor', 'product_overview_input', 'product_overview'))">
            <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Product Overview</label>
            <div id="product_overview_editor"
                class="min-h-[200px] rounded-lg border border-input bg-background dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
            </div>
            <input type="hidden" id="product_overview_input" wire:model="product_overview">
        </div>

        <div wire:ignore x-data x-init="$nextTick(() => window.initCKEditor('main_feature_editor', 'main_feature_input', 'main_feature'))">
            <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Main Features</label>
            <div id="main_feature_editor"
                class="min-h-[200px] rounded-lg border border-input bg-background dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
            </div>
            <input type="hidden" id="main_feature_input" wire:model="main_feature">
        </div>

        <div wire:ignore x-data x-init="$nextTick(() => window.initCKEditor('info_editor', 'info_input', 'information'))">
            <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Information</label>
            <div id="info_editor"
                class="min-h-[200px] rounded-lg border border-input bg-background dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
            </div>
            <input type="hidden" id="info_input" wire:model="information">
        </div>
    </div>

    <!-- Images -->
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-2">Images</label>
        
        <!-- Existing Images (Edit Mode Only) -->
        @if(count($storedImages) > 0)
            <div class="space-y-4 mb-6">
                <h4 class="text-sm font-medium text-gray-600 dark:text-zinc-400">Existing Images</h4>
                @foreach($storedImages as $index => $img)
                    <div class="flex items-center gap-4 p-4 border rounded-lg bg-gray-50 dark:bg-zinc-800 dark:border-zinc-700">
                        <img src="{{ Storage::url($img['image_path']) }}" class="h-20 w-20 object-cover rounded">
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Sequence</label>
                            <input type="number" wire:model="storedImages.{{ $index }}.sequence" class="w-24 rounded border px-2 py-1 text-sm dark:bg-zinc-700 dark:text-white dark:border-zinc-600">
                        </div>
                        <button type="button" wire:confirm="Are you sure?" wire:click="deleteImage({{ $img['id'] }})" class="text-red-500 hover:text-red-700 dark:text-red-400">
                            Remove
                        </button>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- New Images List -->
        <div class="space-y-4">
             <div class="flex justify-between items-center">
                <h4 class="text-sm font-medium text-gray-600 dark:text-zinc-400">New Images</h4>
                <button type="button" wire:click="addImage" class="px-3 py-1.5 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    + Add Image
                </button>
            </div>

            @foreach($newImages as $index => $imgData)
                <div class="flex items-start gap-4 p-4 border rounded-lg bg-white dark:bg-zinc-900 dark:border-zinc-700" wire:key="new-image-{{ $imgData['key'] }}">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Image File</label>
                        <input type="file" wire:model="newImages.{{ $index }}.image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:text-zinc-300 dark:file:bg-zinc-700 dark:file:text-zinc-100">
                        @error("newImages.{$index}.image") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        
                        @if(isset($newImages[$index]['image']) && $newImages[$index]['image'])
                            <div class="mt-2">
                                <img src="{{ $newImages[$index]['image']->temporaryUrl() }}" class="h-20 w-auto rounded border">
                            </div>
                        @endif
                    </div>
                    
                    <div class="w-32">
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Sequence</label>
                        <input type="number" wire:model="newImages.{{ $index }}.sequence" class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                        @error("newImages.{$index}.sequence") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <button type="button" wire:click="removeNewImage({{ $index }})" class="mt-7 text-red-500 hover:text-red-700 dark:text-red-400 p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            @endforeach
            
            @if(empty($newImages) && empty($storedImages))
                <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg dark:border-zinc-700">
                    <p class="text-gray-500 dark:text-zinc-400">No images added yet. Click "Add Image" to start.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- SEO -->
    <div class="border-t pt-4 dark:border-zinc-700">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">SEO Metadata</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">SEO Title</label>
                <input type="text" wire:model="seo_title"
                    class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">SEO Description</label>
                <textarea wire:model="seo_description" rows="3"
                    class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">SEO Keywords</label>
                <input type="text" wire:model="seo_keywords"
                    class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
            </div>
        </div>
    </div>

    <!-- Scripts for this specific form -->
    <script>
        document.addEventListener('livewire:navigated', () => {
             if (window.initCKEditor) {
                window.initCKEditor('key_feature_editor', 'key_feature_input', 'key_feature');
                window.initCKEditor('product_overview_editor', 'product_overview_input', 'product_overview');
                window.initCKEditor('main_feature_editor', 'main_feature_input', 'main_feature');
                window.initCKEditor('info_editor', 'info_input', 'information');
            }
    });
    </script>
</div>