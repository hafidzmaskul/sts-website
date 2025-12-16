<div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Main Content (2/3 width) -->
        <div class="lg:col-span-2 space-y-8">

            <!-- General Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">General Information</h2>
                    <p class="mt-1 text-sm text-gray-500">Basic details about your product.</p>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title <span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model.live="title" placeholder="e.g. Premium Wireless Headphones"
                            class="w-full rounded-lg border px-3 py-2 text-black bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-indigo-500">
                        @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                            <div class="flex rounded-lg shadow-sm">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-[#D2D2D2] bg-gray-50 text-gray-500 sm:text-sm">/product/</span>
                                <input type="text" wire:model="slug" placeholder="premium-wireless-headphones"
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-lg border border-[#D2D2D2] text-black bg-white focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            @error('slug') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                            <select wire:model="brand_id"
                                class="w-full rounded-lg border px-3 py-2 text-black bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select a Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Key Features (Moved up) -->
                    <div wire:ignore x-data
                        x-init="$nextTick(() => window.initCKEditor('key_feature_editor', 'key_feature_input', 'key_feature'))">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Key Features</label>
                        <div id="key_feature_editor" class="prose max-w-none border-[#D2D2D2] rounded-lg"></div>
                        <input type="hidden" id="key_feature_input" wire:model="key_feature">
                    </div>
                    <!-- Overview -->
                    <div wire:ignore x-data
                        x-init="$nextTick(() => window.initCKEditor('product_overview_editor', 'product_overview_input', 'product_overview'))">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Overview</label>
                        <div id="product_overview_editor" class="prose max-w-none border-[#D2D2D2] rounded-lg"></div>
                        <input type="hidden" id="product_overview_input" wire:model="product_overview">
                    </div>

                </div>
            </div>

            <!-- Detailed Details Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Detailed Specifications</h2>
                    <p class="mt-1 text-sm text-gray-500">In-depth features and technical information.</p>
                </div>
                <div class="p-6 space-y-6">
                    <div wire:ignore x-data
                        x-init="$nextTick(() => window.initCKEditor('main_feature_editor', 'main_feature_input', 'main_feature'))">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Main Features</label>
                        <div id="main_feature_editor" class="prose max-w-none border-[#D2D2D2] rounded-lg"></div>
                        <input type="hidden" id="main_feature_input" wire:model="main_feature">
                    </div>

                    <div wire:ignore x-data x-init="$nextTick(() => window.initCKEditor('specification_editor', 'specification_input', 'specification'))">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Specification</label>
                        <div id="specification_editor" class="prose max-w-none border-[#D2D2D2] rounded-lg"></div>
                        <input type="hidden" id="specification_input" wire:model="specification">
                    </div>

                    <div wire:ignore x-data
                        x-init="$nextTick(() => window.initCKEditor('info_editor', 'info_input', 'information'))">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Additional Information</label>
                        <div id="info_editor" class="prose max-w-none border-[#D2D2D2] rounded-lg"></div>
                        <input type="hidden" id="info_input" wire:model="information">
                    </div>
                </div>
            </div>

            <!-- SEO Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">SEO Optimized</h2>
                    <p class="mt-1 text-sm text-gray-500">Improve your product's visibility on search engines.</p>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <div class="flex justify-between">
                            <label class="block text-sm font-medium text-gray-700 mb-1">SEO Title</label>
                            <span class="text-xs text-gray-500">{{ strlen($seo_title ?? '') }} / 60</span>
                        </div>
                        <input type="text" wire:model="seo_title"
                            class="w-full rounded-lg border px-3 py-2 text-black bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <div class="flex justify-between">
                            <label class="block text-sm font-medium text-gray-700 mb-1">SEO Description</label>
                            <span class="text-xs text-gray-500">{{ strlen($seo_description ?? '') }} / 160</span>
                        </div>
                        <textarea wire:model="seo_description" rows="3"
                            class="w-full rounded-lg border px-3 py-2 text-black bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SEO Keywords</label>
                        <input type="text" wire:model="seo_keywords" placeholder="Comma separated keywords"
                            class="w-full rounded-lg border px-3 py-2 text-black bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Sidebar (1/3 width) -->
        <div class="lg:col-span-1 space-y-8">

            <!-- Publishing Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Publishing</h2>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select wire:model="status"
                            class="w-full rounded-lg border px-3 py-2 text-black bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Base Price (GBP)</label>
                        <div class="relative rounded-lg shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                                <span class="text-gray-500 sm:text-sm">£</span>
                            </div>
                            <input type="number" step="0.01" wire:model="base_price"
                                class="w-full rounded-lg border pl-7 pr-3 py-2 text-black bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="0.00">
                        </div>
                        @error('base_price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-3 pt-2">
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox" wire:model="is_sign_up_for_pricing"
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mt-1">
                            <span class="ml-2 text-sm text-gray-700">
                                <span class="font-medium text-gray-900 block">Sign Up for Pricing</span>
                                <span class="text-gray-500">Hide price and show inquiry form.</span>
                            </span>
                        </label>
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox" wire:model="is_exclusive"
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mt-1">
                            <span class="ml-2 text-sm text-gray-700">
                                <span class="font-medium text-gray-900 block">Exclusive Product</span>
                                <span class="text-gray-500">Mark as exclusive item.</span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Categories Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Categories</h2>
                </div>
                <div class="p-4">
                    <div class="max-h-60 overflow-y-auto space-y-2 pr-2 custom-scrollbar">
                        @php
                            $groupedCategories = $categories->groupBy('parent_id');
                            $parents = $groupedCategories->get('') ?? $groupedCategories->get(null) ?? collect();
                        @endphp
                        @foreach($parents as $parent)
                            <div class="space-y-1">
                                <label class="flex items-center p-2 rounded hover:bg-gray-50 w-full cursor-pointer">
                                    <input type="checkbox" wire:model="selectedCategories" value="{{ $parent->id }}"
                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm font-semibold text-gray-900">{{ $parent->name }}</span>
                                </label>
                                @if($children = $groupedCategories->get($parent->id))
                                    <div class="pl-6 space-y-1 border-l-2 border-gray-100 ml-2">
                                        @foreach($children as $child)
                                            <label class="flex items-center p-1.5 rounded hover:bg-gray-50 w-full cursor-pointer">
                                                <input type="checkbox" wire:model="selectedCategories" value="{{ $child->id }}"
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-gray-700">{{ $child->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        <!-- Handling orphans if any -->
                        @foreach($groupedCategories as $parentId => $children)
                            @if($parentId && !$categories->contains('id', $parentId))
                                <div class="space-y-1">
                                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">
                                        Uncategorized</div>
                                    @foreach($children as $child)
                                        <label class="flex items-center p-1.5 rounded hover:bg-gray-50 w-full cursor-pointer">
                                            <input type="checkbox" wire:model="selectedCategories" value="{{ $child->id }}"
                                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-700">{{ $child->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Images Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Images</h2>
                    <button type="button" wire:click="addImage"
                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Add Image
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Existing Images -->
                    @if(count($storedImages) > 0)
                        <div class="space-y-3">
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Saved Images</div>
                            @foreach($storedImages as $index => $img)
                                <div
                                    class="group flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-lg relative">
                                    <img src="{{ Storage::url($img['image_path']) }}"
                                        class="h-16 w-16 object-cover rounded bg-white border border-gray-200">
                                    <div class="flex-1 min-w-0">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Order</label>
                                        <input type="number" wire:model="storedImages.{{ $index }}.sequence"
                                            class="block w-20 rounded border-gray-300 text-xs py-1 px-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <button type="button" wire:confirm="Remove this image?"
                                        wire:click="deleteImage({{ $img['id'] }})"
                                        class="text-gray-400 hover:text-red-500 p-1 rounded-full hover:bg-red-50 transition-colors">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- New Images -->
                    @if(count($newImages) > 0)
                        <div class="space-y-3">
                            <div class="text-xs font-medium text-green-600 uppercase tracking-wide">New Uploads</div>
                            @foreach($newImages as $index => $imgData)
                                <div class="group p-3 bg-white border border-dashed border-indigo-300 rounded-lg relative"
                                    wire:key="new-image-{{ $imgData['key'] }}">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-1 min-w-0">
                                            <input type="file" wire:model="newImages.{{ $index }}.image"
                                                class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                            @error("newImages.{$index}.image") <span
                                            class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror

                                            @if(isset($newImages[$index]['image']) && $newImages[$index]['image'])
                                                <div class="mt-2">
                                                    <img src="{{ $newImages[$index]['image']->temporaryUrl() }}"
                                                        class="h-16 w-auto rounded border border-gray-200">
                                                </div>
                                            @endif
                                        </div>
                                        <div class="w-16">
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Order</label>
                                            <input type="number" wire:model="newImages.{{ $index }}.sequence"
                                                class="block w-full rounded border-gray-300 text-xs py-1 px-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                        <button type="button" wire:click="removeNewImage({{ $index }})"
                                            class="absolute -top-2 -right-2 bg-white text-gray-400 hover:text-red-500 border border-gray-200 rounded-full p-1 shadow-sm hover:shadow">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @error("newImages.{$index}.sequence") <span
                                    class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if(empty($newImages) && empty($storedImages))
                        <div class="text-center py-6 bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-1 text-xs text-gray-500">No images yet.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('livewire:navigated', () => {
            if (window.initCKEditor) {
                window.initCKEditor('key_feature_editor', 'key_feature_input', 'key_feature');
                window.initCKEditor('product_overview_editor', 'product_overview_input', 'product_overview');
                window.initCKEditor('main_feature_editor', 'main_feature_input', 'main_feature');
                window.initCKEditor('specification_editor', 'specification_input', 'specification');
                window.initCKEditor('info_editor', 'info_input', 'information');
            }
        });
    </script>
</div>