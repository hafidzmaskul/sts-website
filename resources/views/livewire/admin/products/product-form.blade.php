<div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8" style="color: black;">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Main Content (2/3 width) -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Images Card (Moved for better UX) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold" style="color: black;">Product Images</h2>
                        <p class="mt-1 text-sm text-gray-500">Upload and manage product gallery.</p>
                    </div>
                    <button type="button" wire:click="addImage"
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-lg text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
                        Add Image
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <!-- New Images -->
                    @if(count($newImages) > 0)
                        <div class="space-y-3">
                            <div class="text-xs font-semibold text-green-600 uppercase tracking-wide px-1">New Uploads</div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                @foreach($newImages as $index => $imgData)
                                    <div class="group p-3 bg-white border-2 border-dashed border-indigo-300 rounded-xl relative"
                                        wire:key="new-image-{{ $imgData['key'] }}">
                                        <div class="space-y-3">
                                            <div class="relative aspect-square bg-gray-100 rounded-lg overflow-hidden">
                                                @if(isset($newImages[$index]['image']) && $newImages[$index]['image'])
                                                    <img src="{{ $newImages[$index]['image']->temporaryUrl() }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="flex items-center justify-center h-full text-gray-400">
                                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                    </div>
                                                @endif
                                                <input type="file" wire:model="newImages.{{ $index }}.image" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                                            </div>
                                            
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Order</label>
                                                <input type="number" wire:model="newImages.{{ $index }}.sequence"
                                                    class="block w-full rounded-md border-gray-300 text-xs py-1 px-2 focus:ring-btn-primary-ring focus:border-indigo-500 bg-gray-50"
                                                    style="color: black;">
                                            </div>
                                        </div>
                                        <button type="button" wire:click="removeNewImage({{ $index }})"
                                            class="absolute -top-2 -right-2 bg-white text-gray-400 hover:text-red-500 border border-gray-200 rounded-full p-1.5 shadow-sm hover:shadow transition-all">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                        @error("newImages.{$index}.image") <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                        @error("newImages.{$index}.sequence") <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Existing Images -->
                    @if(count($storedImages) > 0)
                        <div class="space-y-3 pt-4 @if(count($newImages) > 0) border-t border-gray-100 @endif">
                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide px-1">Saved Images</div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach($storedImages as $index => $img)
                                    <div class="group relative bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all">
                                        <div class="aspect-square bg-gray-100 relative">
                                            <img src="{{ Storage::url($img['image_path']) }}" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                                        </div>
                                        <div class="p-3 border-t border-gray-100 bg-gray-50/50">
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Order</label>
                                            <input type="number" wire:model="storedImages.{{ $index }}.sequence"
                                                class="block w-full rounded-md border-gray-300 text-xs py-1 px-2 focus:ring-btn-primary-ring focus:border-indigo-500 bg-white"
                                                style="color: black;">
                                        </div>
                                        <button type="button" wire:confirm="Remove this image?" wire:click="deleteImage({{ $img['id'] }})"
                                            class="absolute top-2 right-2 bg-white/90 text-gray-400 hover:text-red-500 border border-gray-200 rounded-full p-1.5 shadow-sm opacity-0 group-hover:opacity-100 transition-all">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(empty($newImages) && empty($storedImages))
                        <div class="text-center py-12 bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No images uploaded yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Categories Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold" style="color: black;">Categories</h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Categories -->
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: black;">Categories</label>
                        <div class="border border-gray-200 rounded-lg p-4 max-h-60 overflow-y-auto custom-scrollbar">
                            @php
                                $groupedCategories = $categories->groupBy('parent_id');
                                $parents = $groupedCategories->get('') ?? $groupedCategories->get(null) ?? collect();
                            @endphp
                            @foreach($parents as $parent)
                                <x-product-category-checkbox :category="$parent" :grouped-categories="$groupedCategories" />
                            @endforeach
                            <!-- Handling orphans if any -->
                            @foreach($groupedCategories as $parentId => $children)
                                @if($parentId && !$categories->contains('id', $parentId))
                                    <div class="space-y-1">
                                        <div class="text-xs font-semibold uppercase tracking-wider px-2 mt-2"
                                            style="color: #9ca3af;">
                                            Uncategorized</div>
                                        @foreach($children as $child)
                                            <x-product-category-checkbox :category="$child" :grouped-categories="$groupedCategories" />
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- General Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold" style="color: black;">General Information</h2>
                    <p class="mt-1 text-sm" style="color: #6b7280;">Basic details about your product.</p>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-medium" style="color: black;">Title <span class="text-red-500">*</span></label>
                        <input type="text" wire:model.live="title" placeholder="e.g. Premium Wireless Headphones"
                            class="w-full rounded-lg border px-3 py-2 bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-btn-primary-ring"
                            style="color: black;">
                        @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium" style="color: black;">Slug</label>
                            <div class="flex rounded-lg shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-[#D2D2D2] bg-gray-50"
                                    style="color: #6b7280;">/product/</span>
                                <input type="text" wire:model="slug" placeholder="premium-wireless-headphones"
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-lg border border-[#D2D2D2] bg-white focus:ring-btn-primary-ring focus:border-indigo-500"
                                    style="color: black;">
                            </div>
                            @error('slug') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium" style="color: black;">SKU</label>
                            <input type="text" wire:model="sku" placeholder="e.g. SKU-12345"
                                class="w-full rounded-lg border px-3 py-2 bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-btn-primary-ring"
                                style="color: black;">
                            @error('sku') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Key Features -->
                    <div wire:ignore x-data
                        x-init="$nextTick(() => window.initCKEditor('key_feature_editor', 'key_feature_input', 'key_feature'))">
                        <label class="block text-sm font-medium" style="color: black;">Key Features</label>
                        <div id="key_feature_editor" class="prose max-w-none border-[#D2D2D2] rounded-lg"></div>
                        <input type="hidden" id="key_feature_input" wire:model="key_feature">
                    </div>
                    <!-- Overview -->
                    <div wire:ignore x-data
                        x-init="$nextTick(() => window.initCKEditor('product_overview_editor', 'product_overview_input', 'product_overview'))">
                        <label class="block text-sm font-medium" style="color: black;">Product Overview</label>
                        <div id="product_overview_editor" class="prose max-w-none border-[#D2D2D2] rounded-lg"></div>
                        <input type="hidden" id="product_overview_input" wire:model="product_overview">
                    </div>

                </div>
            </div>

            <!-- Detailed Details Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold" style="color: black;">Detailed Specifications</h2>
                    <p class="mt-1 text-sm" style="color: #6b7280;">In-depth features and technical information.</p>
                </div>
                <div class="p-6 space-y-6">
                    <div wire:ignore x-data
                        x-init="$nextTick(() => window.initCKEditor('main_feature_editor', 'main_feature_input', 'main_feature'))">
                        <label class="block text-sm font-medium" style="color: black;">Main Features</label>
                        <div id="main_feature_editor" class="prose max-w-none border-[#D2D2D2] rounded-lg"></div>
                        <input type="hidden" id="main_feature_input" wire:model="main_feature">
                    </div>

                    <div wire:ignore x-data
                        x-init="$nextTick(() => window.initCKEditor('specification_editor', 'specification_input', 'specification'))">
                        <label class="block text-sm font-medium" style="color: black;">Specification</label>
                        <div id="specification_editor" class="prose max-w-none border-[#D2D2D2] rounded-lg"></div>
                        <input type="hidden" id="specification_input" wire:model="specification">
                    </div>

                    <div wire:ignore x-data
                        x-init="$nextTick(() => window.initCKEditor('info_editor', 'info_input', 'information'))">
                        <label class="block text-sm font-medium" style="color: black;">Additional Information</label>
                        <div id="info_editor" class="prose max-w-none border-[#D2D2D2] rounded-lg"></div>
                        <input type="hidden" id="info_input" wire:model="information">
                    </div>
                </div>
            </div>

            <!-- Attachments Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Attachments</h2>
                        <p class="mt-1 text-sm text-gray-500">Manage technical documents and manuals.</p>
                    </div>
                </div>
                <div class="p-6 space-y-6">

                    <!-- Drag & Drop Area for New Attachment -->
                    <div wire:click="addAttachment"
                        class="cursor-pointer border-2 border-dashed border-indigo-200 rounded-xl p-6 flex flex-col items-center justify-center bg-indigo-50/50 hover:bg-indigo-50 hover:border-indigo-400 transition-all group">
                        <div class="p-3 bg-white rounded-full shadow-sm mb-3 group-hover:scale-110 transition-transform">
                             <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-indigo-900">Click to add new attachment</p>
                        <p class="text-xs text-indigo-500 mt-1">PDF, DOC, DOCX up to 10MB</p>
                    </div>

                    <!-- New Attachments List -->
                    @if(count($newAttachments) > 0)
                        <div class="space-y-4 animate-fade-in-down">
                            <div class="text-xs font-semibold text-green-600 uppercase tracking-wide px-1">New Uploads</div>
                            @foreach($newAttachments as $index => $attData)
                                <div class="group p-5 bg-white border border-indigo-100 shadow-sm rounded-xl relative hover:border-indigo-300 transition-colors"
                                    wire:key="new-attachment-{{ $attData['key'] }}">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                                        <!-- File Input & Name -->
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Display Name</label>
                                                <input type="text" wire:model="newAttachments.{{ $index }}.name" placeholder="e.g. User Manual"
                                                    class="block w-full rounded-lg border border-[#D2D2D2] px-3 py-2 bg-white focus:border-indigo-500 focus:ring-btn-primary-ring text-sm placeholder-gray-400" style="color: black;">
                                                @error("newAttachments.{$index}.name") <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">File</label>
                                                <input type="file" wire:model="newAttachments.{{ $index }}.file"
                                                    class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                                                @error("newAttachments.{$index}.file") <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        
                                        <!-- Settings & Actions -->
                                        <div class="flex flex-col justify-between h-full pt-6 md:pt-0">
                                            <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg border border-gray-100 hover:bg-gray-50 transition-colors">
                                                <input type="checkbox" wire:model="newAttachments.{{ $index }}.is_public" 
                                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-medium text-gray-900">Publicly Visible</span>
                                                    <span class="text-xs text-gray-500">Visible to all users on product page</span>
                                                </div>
                                            </label>
                                        </div>

                                        <button type="button" wire:click="removeNewAttachment({{ $index }})"
                                            class="absolute -top-3 -right-3 bg-white text-gray-400 hover:text-red-500 border border-gray-200 rounded-full p-1.5 shadow-sm hover:shadow-md transition-all">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Existing Attachments List -->
                    @if(count($storedAttachments) > 0)
                        <div class="space-y-4 pt-4 border-t border-gray-100">
                             <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide px-1">Saved Attachments</div>
                            @foreach($storedAttachments as $index => $att)
                                <div class="flex flex-col md:flex-row items-start md:items-center gap-4 p-4 bg-gray-50/50 border border-gray-200 rounded-xl relative group hover:bg-white hover:shadow-sm transition-all">
                                    
                                    <!-- Icon -->
                                    <div class="flex-shrink-0 bg-white p-2 rounded-lg border border-gray-200 text-gray-400">
                                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>

                                    <!-- Inputs -->
                                    <div class="flex-1 min-w-0 w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Display Name</label>
                                            <input type="text" wire:model="storedAttachments.{{ $index }}.name"
                                                class="block w-full rounded-lg border border-[#D2D2D2] px-3 py-2 bg-white focus:border-indigo-500 focus:ring-btn-primary-ring text-sm" style="color: black;">
                                        </div>
                                        <div class="flex items-end justify-between gap-4">
                                             <label class="flex items-center gap-2 cursor-pointer pb-2">
                                                <input type="checkbox" wire:model="storedAttachments.{{ $index }}.is_public" 
                                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="text-sm text-gray-600 font-medium">Public</span>
                                            </label>
                                            
                                            <div class="flex items-center gap-2 pb-1">
                                                <a href="{{ Storage::url($att['file_path']) }}" target="_blank" 
                                                    class="inline-flex items-center px-3 py-1.5 border border-gray-200 shadow-sm text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                                    View File
                                                </a>
                                                 <button type="button" wire:confirm="Remove this attachment?"
                                                    wire:click="deleteAttachment({{ $att['id'] }})"
                                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    @if(empty($newAttachments) && empty($storedAttachments))
                         <div class="text-center py-8">
                            <p class="text-sm text-gray-500">No attachments uploaded yet.</p>
                        </div>
                    @endif

                </div>

            </div>

            <!-- Variants Card -->
            @if(isset($productId))
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-semibold" style="color: black;">Product Variants</h2>
                            <p class="mt-1 text-sm text-gray-500">Manage multiple options/variants for this product.</p>
                        </div>
                        <button type="button" wire:click="addVariant"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm">
                            <svg class="h-5 w-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add Variant
                        </button>
                    </div>
                    <div class="p-6 space-y-6">
                        @if(count($variants) > 0)
                            <div class="space-y-6">
                                @foreach($variants as $vIndex => $variant)
                                    <div class="p-6 bg-gray-50 rounded-xl border border-gray-200 relative space-y-6" wire:key="variant-{{ $vIndex }}">
                                        <!-- Variant Header -->
                                        <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                                            <h3 class="text-md font-semibold text-gray-900">
                                                Variant #{{ $vIndex + 1 }}: {{ $variant['title'] ?: 'New Variant' }}
                                            </h3>
                                            <button type="button" wire:click="removeVariant({{ $vIndex }})"
                                                class="inline-flex items-center px-2 py-1 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-md transition-colors border border-red-200">
                                                Remove Variant
                                            </button>
                                        </div>

                                        <!-- Variant Inputs -->
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                                                <input type="text" wire:model.live="variants.{{ $vIndex }}.title" placeholder="e.g. Red / XL"
                                                    class="block w-full rounded-lg border border-[#D2D2D2] px-3 py-2 bg-white focus:border-indigo-500 focus:ring-btn-primary-ring text-sm" style="color: black;">
                                                @error("variants.{$vIndex}.title") <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">SKU <span class="text-red-500">*</span></label>
                                                <input type="text" wire:model.live="variants.{{ $vIndex }}.sku" placeholder="e.g. SKU-RED-XL"
                                                    class="block w-full rounded-lg border border-[#D2D2D2] px-3 py-2 bg-white focus:border-indigo-500 focus:ring-btn-primary-ring text-sm" style="color: black;">
                                                @error("variants.{$vIndex}.sku") <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                                <select wire:model="variants.{{ $vIndex }}.status"
                                                    class="block w-full rounded-lg border border-[#D2D2D2] px-3 py-2 bg-white focus:border-indigo-500 focus:ring-btn-primary-ring text-sm" style="color: black;">
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                </select>
                                                @error("variants.{$vIndex}.status") <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Variant Image</label>
                                                <div class="flex items-center gap-2 mt-1">
                                                    @if(!empty($variant['image_path']))
                                                        <div class="relative group h-10 w-10 border rounded overflow-hidden flex-shrink-0">
                                                            <img src="{{ Storage::url($variant['image_path']) }}" class="h-full w-full object-cover">
                                                            <button type="button" wire:confirm="Remove this variant image?" wire:click="deleteVariantImage({{ $vIndex }})"
                                                                class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white transition-opacity">
                                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                            </button>
                                                        </div>
                                                    @elseif(isset($variant['image']) && $variant['image'])
                                                        <div class="relative group h-10 w-10 border rounded overflow-hidden flex-shrink-0">
                                                            <img src="{{ $variant['image']->temporaryUrl() }}" class="h-full w-full object-cover">
                                                            <button type="button" wire:click="deleteVariantImage({{ $vIndex }})"
                                                                class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white transition-opacity">
                                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <div class="h-10 w-10 border border-dashed rounded flex-shrink-0 flex items-center justify-center bg-gray-100 text-gray-400">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                        </div>
                                                    @endif
                                                    <input type="file" wire:model="variants.{{ $vIndex }}.image" class="block w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                                                </div>
                                                @error("variants.{$vIndex}.image") <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Variant Pricing Details -->
                                        <div class="bg-white p-4 rounded-lg border border-gray-200 space-y-4">
                                            <h4 class="text-sm font-semibold text-gray-800 border-b pb-1">Pricing Configuration</h4>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Base Price (GBP)</label>
                                                    <input type="number" step="0.01" wire:model.live="variants.{{ $vIndex }}.base_price" placeholder="0.00"
                                                        class="block w-full rounded-lg border border-[#D2D2D2] px-3 py-1.5 bg-white text-xs" style="color: black;">
                                                    @error("variants.{$vIndex}.base_price") <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="flex items-end text-xs text-gray-500 pb-2 italic">
                                                    Calculated Price matches parent Pricing formula if Base Price is not set.
                                                </div>
                                            </div>

                                            <!-- Special Price inside Variant -->
                                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 space-y-3">
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">Special Price (GBP)</label>
                                                        <input type="number" step="0.01" wire:model.live="variants.{{ $vIndex }}.special_price" placeholder="0.00"
                                                            class="block w-full rounded-lg border border-[#D2D2D2] px-3 py-1.5 bg-white text-xs" style="color: black;">
                                                        @error("variants.{$vIndex}.special_price") <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">Start Date</label>
                                                        <input type="datetime-local" wire:model.live="variants.{{ $vIndex }}.special_price_start"
                                                            class="block w-full rounded-lg border border-[#D2D2D2] px-3 py-1 text-xs bg-white" style="color: black;">
                                                        @error("variants.{$vIndex}.special_price_start") <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">End Date</label>
                                                        <input type="datetime-local" wire:model.live="variants.{{ $vIndex }}.special_price_end"
                                                            class="block w-full rounded-lg border border-[#D2D2D2] px-3 py-1 text-xs bg-white" style="color: black;">
                                                        @error("variants.{$vIndex}.special_price_end") <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Checkboxes inside Variant -->
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" wire:model="variants.{{ $vIndex }}.is_sign_up_for_pricing" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                    <span class="ml-2 text-xs text-gray-700 font-medium">Sign Up for Pricing</span>
                                                </label>
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="checkbox" wire:model="variants.{{ $vIndex }}.is_cta" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                    <span class="ml-2 text-xs text-gray-700 font-medium">Is CTA Product</span>
                                                </label>
                                            </div>

                                            <!-- Quantity Pricing Tiers for Variant -->
                                            <div class="border-t border-gray-200 pt-4">
                                                <div class="flex justify-between items-center mb-2">
                                                    <span class="text-xs font-semibold text-gray-700">Quantity Pricing Tiers</span>
                                                    <button type="button" wire:click="addVariantQuantityPrice({{ $vIndex }})"
                                                        class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                                                        + Add Tier
                                                    </button>
                                                </div>
                                                @if(count($variant['quantityPrices'] ?? []) > 0)
                                                    <div class="space-y-2">
                                                        @foreach($variant['quantityPrices'] as $qpIndex => $qp)
                                                            <div class="flex items-center gap-3 p-2 bg-gray-50 rounded border border-gray-200" wire:key="var-{{ $vIndex }}-qp-{{ $qpIndex }}">
                                                                <div class="flex-1 grid grid-cols-2 gap-2">
                                                                    <div>
                                                                        <input type="number" min="1" step="1" wire:model="variants.{{ $vIndex }}.quantityPrices.{{ $qpIndex }}.quantity" placeholder="Qty"
                                                                            class="block w-full rounded border border-[#D2D2D2] px-2 py-1 bg-white text-xs" style="color: black;">
                                                                        @error("variants.{$vIndex}.quantityPrices.{$qpIndex}.quantity") <span class="text-red-500 text-xxs block mt-0.5">{{ $message }}</span> @enderror
                                                                    </div>
                                                                    <div>
                                                                        <input type="number" step="0.01" wire:model="variants.{{ $vIndex }}.quantityPrices.{{ $qpIndex }}.price" placeholder="Price (GBP)"
                                                                            class="block w-full rounded border border-[#D2D2D2] px-2 py-1 bg-white text-xs" style="color: black;">
                                                                        @error("variants.{$vIndex}.quantityPrices.{$qpIndex}.price") <span class="text-red-500 text-xxs block mt-0.5">{{ $message }}</span> @enderror
                                                                    </div>
                                                                </div>
                                                                <button type="button" wire:click="removeVariantQuantityPrice({{ $vIndex }}, {{ $qpIndex }})"
                                                                    class="text-gray-400 hover:text-red-500 transition-colors">
                                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Advance Customer Pricing for Variant -->
                                            <div class="border-t border-gray-200 pt-4">
                                                <div class="flex justify-between items-center mb-2">
                                                    <label class="flex items-center cursor-pointer">
                                                        <input type="checkbox" wire:model.live="variants.{{ $vIndex }}.showAdvancePricing" class="h-3.5 w-3.5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                        <span class="ml-2 text-xs font-semibold text-gray-700">Customer Specific Prices</span>
                                                    </label>
                                                    @if($variant['showAdvancePricing'])
                                                        <button type="button" wire:click="addVariantCustomerPrice({{ $vIndex }})"
                                                            class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                                                            + Add Customer
                                                        </button>
                                                    @endif
                                                </div>
                                                @if($variant['showAdvancePricing'] && count($variant['customerPrices'] ?? []) > 0)
                                                    <div class="space-y-2">
                                                        @foreach($variant['customerPrices'] as $cpIndex => $cp)
                                                            <div class="flex items-center gap-3 p-2 bg-gray-50 rounded border border-gray-200" wire:key="var-{{ $vIndex }}-cp-{{ $cpIndex }}">
                                                                <div class="flex-1 grid grid-cols-2 gap-2">
                                                                    <div>
                                                                        <select wire:model="variants.{{ $vIndex }}.customerPrices.{{ $cpIndex }}.user_id"
                                                                            class="block w-full rounded border border-[#D2D2D2] px-2 py-1 bg-white text-xs" style="color: black;">
                                                                            <option value="">Select Customer</option>
                                                                            @foreach($customers as $c)
                                                                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->email }})</option>
                                                                            @endforeach
                                                                        </select>
                                                                        @error("variants.{$vIndex}.customerPrices.{$cpIndex}.user_id") <span class="text-red-500 text-xxs block mt-0.5">{{ $message }}</span> @enderror
                                                                    </div>
                                                                    <div>
                                                                        <input type="number" step="0.01" wire:model="variants.{{ $vIndex }}.customerPrices.{{ $cpIndex }}.price" placeholder="Price"
                                                                            class="block w-full rounded border border-[#D2D2D2] px-2 py-1 bg-white text-xs" style="color: black;">
                                                                        @error("variants.{$vIndex}.customerPrices.{$cpIndex}.price") <span class="text-red-500 text-xxs block mt-0.5">{{ $message }}</span> @enderror
                                                                    </div>
                                                                </div>
                                                                <button type="button" wire:click="removeVariantCustomerPrice({{ $vIndex }}, {{ $cpIndex }})"
                                                                    class="text-gray-400 hover:text-red-500 transition-colors">
                                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 font-medium">No variants created for this product yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>

        <!-- Right Column: Sidebar (1/3 width) -->
        <div class="lg:col-span-1 space-y-8">

            <!-- Pricing Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold" style="color: black;">Pricing</h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Brand & Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium" style="color: black;">Brand <span class="text-red-500">*</span></label>
                            <select wire:model.live="brand_id"
                                class="w-full rounded-lg border px-3 py-2 bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-btn-primary-ring"
                                style="color: black;">
                                <option value="">Select a Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium" style="color: black;">Status <span class="text-red-500">*</span></label>
                            <select wire:model="status"
                                class="w-full rounded-lg border px-3 py-2 bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-btn-primary-ring"
                                style="color: black;">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Pricing Mode -->
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: black;">Pricing Mode</label>
                        <div class="flex items-center space-x-4">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" wire:model.live="pricing_mode" value="auto" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Auto (Formula)</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" wire:model.live="pricing_mode" value="manual" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Manual (Fixed)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Base Cost / RSP (Always visible or conditional?) -->
                    <!-- Cost is always useful for auto. RSP is needed for discount. Let's show both if auto. -->
                    @if($pricing_mode === 'auto')
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium" style="color: black;">Cost (GBP)</label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                                        <span style="color: #6b7280;" class="sm:text-sm">£</span>
                                    </div>
                                    <input type="number" step="0.01" wire:model.live.debounce.500ms="cost"
                                        class="w-full rounded-lg border pl-7 pr-3 py-2 bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="0.00" style="color: black;">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium" style="color: black;">RSP (GBP)</label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                                        <span style="color: #6b7280;" class="sm:text-sm">£</span>
                                    </div>
                                    <input type="number" step="0.01" wire:model.live.debounce.500ms="rsp"
                                        class="w-full rounded-lg border pl-7 pr-3 py-2 bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="0.00" style="color: black;">
                                </div>
                            </div>
                        </div>

                        <!-- Auto Pricing Options -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 space-y-4">
                            <div>
                                <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wide">Brand Default Formula</span>
                                @if($brandFormulaLabel)
                                    <span class="text-sm font-medium text-gray-900">{{ $brandFormulaLabel }} ({{ $brandFormulaType }}: {{ (float)$brandFormulaValue }}%)</span>
                                @else
                                    <span class="text-sm text-gray-500 italic">No brand formula set (requires Brand selection)</span>
                                @endif
                            </div>

                            <div>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model.live="override_enabled" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700 font-medium">Override Brand Formula</span>
                                </label>
                            </div>

                            @if($override_enabled)
                                <div class="grid grid-cols-2 gap-4 pt-2 border-t border-gray-200">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Method</label>
                                        <select wire:model.live="override_method" class="w-full rounded-lg border px-3 py-2 bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-indigo-500 text-sm" style="color: black;">
                                            <option value="">Select Method</option>
                                            @foreach($pricingMethods ?? [] as $method)
                                                <option value="{{ $method->value }}">{{ $method->label() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Value (%)</label>
                                        <input type="number" step="0.01" wire:model.live.debounce.500ms="override_value"
                                            class="w-full rounded-lg border px-3 py-2 bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="0.00" style="color: black;">
                                    </div>
                                </div>
                            @endif

                            <div class="pt-2">
                                <span class="block text-xs font-semibold text-indigo-600 uppercase tracking-wide">Calculated Price Preview</span>
                                <div class="text-xl font-bold text-gray-900 mt-1">
                                    @if($this->previewPrice !== null)
                                        £{{ number_format($this->previewPrice, 2) }}
                                    @else
                                        <span class="text-gray-400 text-base italic">Cannot calculate</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Manual Pricing -->
                        <div>
                            <label class="block text-sm font-medium" style="color: black;">Base Price (GBP)</label>
                            <div class="relative rounded-lg shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                                    <span style="color: #6b7280;" class="sm:text-sm">£</span>
                                </div>
                                <input type="number" step="0.01" wire:model.live.debounce.500ms="base_price"
                                    class="w-full rounded-lg border pl-7 pr-3 py-2 bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="0.00" style="color: black;">
                            </div>
                            @error('base_price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <!-- Special Price -->
                    <div class="border-t border-gray-200 pt-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium" style="color: black;">Special/Promo Price (GBP)</label>
                            <div class="relative rounded-lg shadow-sm mt-1">
                                <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                                    <span style="color: #6b7280;" class="sm:text-sm">£</span>
                                </div>
                                <input type="number" step="0.01" wire:model="special_price"
                                    class="w-full rounded-lg border pl-7 pr-3 py-2 bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="0.00" style="color: black;">
                            </div>
                            @error('special_price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Start Date (Optional)</label>
                                <input type="datetime-local" wire:model="special_price_start"
                                    class="mt-1 w-full rounded-lg border px-3 py-2 bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-indigo-500 text-sm" style="color: black;">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700">End Date (Optional)</label>
                                <input type="datetime-local" wire:model="special_price_end"
                                    class="mt-1 w-full rounded-lg border px-3 py-2 bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-indigo-500 text-sm" style="color: black;">
                            </div>

                        </div>
                    </div>

                    <!-- Quantity Pricing -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <span class="block text-sm font-medium" style="color: black;">Quantity Pricing</span>
                                <span class="text-xs" style="color: #6b7280;">Set different prices based on quantity.</span>
                            </div>
                            <button type="button" wire:click="addQuantityPrice"
                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-lg text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                + Add Tier
                            </button>
                        </div>

                        <div class="space-y-3">
                            @foreach($quantityPrices as $index => $qp)
                                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200 relative group"
                                    wire:key="qty-price-{{ $index }}">
                                    <div class="flex-1 grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium" style="color: #6b7280;">Quantity</label>
                                            <input type="number" min="1" step="1"
                                                wire:model="quantityPrices.{{ $index }}.quantity"
                                                class="mt-1 block w-full rounded-lg border border-[#D2D2D2] px-3 py-1.5 bg-white focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                                style="color: black;" placeholder="e.g. 10">
                                            @error("quantityPrices.{$index}.quantity") <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium" style="color: #6b7280;">Price (GBP)</label>
                                            <div class="relative rounded shadow-sm mt-1">
                                                <div class="pointer-events-none absolute inset-y-0 left-0 pl-2 flex items-center">
                                                    <span class="sm:text-xs" style="color: #6b7280;">£</span>
                                                </div>
                                                <input type="number" step="0.01"
                                                    wire:model="quantityPrices.{{ $index }}.price"
                                                    class="block w-full rounded-lg border border-[#D2D2D2] text-sm py-1.5 pl-6 pr-3 bg-white focus:ring-indigo-500 focus:border-indigo-500"
                                                    style="color: black;" placeholder="0.00">
                                            </div>
                                            @error("quantityPrices.{$index}.price") <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <button type="button" wire:click="removeQuantityPrice({{ $index }})"
                                        class="mt-5 flex-shrink-0 text-gray-400 hover:text-red-500 transition-colors">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                    <div class="space-y-3 pt-2">
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox" wire:model="is_sign_up_for_pricing"
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-btn-primary-ring mt-1">
                            <span class="ml-2 text-sm" style="color: black;">
                                <span class="font-medium block" style="color: black;">Sign Up for Pricing</span>
                                <span style="color: #6b7280;">Hide price and show inquiry form.</span>
                            </span>
                        </label>
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox" wire:model.live="showAdvancePricing"
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-btn-primary-ring mt-1">
                            <span class="ml-2 text-sm" style="color: black;">
                                <span class="font-medium block" style="color: black;">Advance Pricing</span>
                                <span style="color: #6b7280;">Enable specific fixed pricing for customers.</span>
                            </span>
                        </label>
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox" wire:model="is_cta"
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-btn-primary-ring mt-1">
                            <span class="ml-2 text-sm" style="color: black;">
                                <span class="font-medium block" style="color: black;">Is CTA Product</span>
                                <span style="color: #6b7280;">Mark this product as a Call to Action product.</span>
                            </span>
                        </label>
                    </div>

                    @if($showAdvancePricing)
                        <div class="pt-4 border-t border-gray-200 space-y-4">
                            <div class="flex justify-between items-center">
                                <label class="block text-sm font-medium" style="color: black;">Customer Pricing</label>
                                <button type="button" wire:click="addCustomerPrice"
                                    class="text-xs text-indigo-600 hover:text-indigo-500 font-medium">
                                    + Add Customer
                                </button>
                            </div>

                            <div class="space-y-3" x-data="{
                                        allCustomers: @js($customers->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'email' => $c->email])->values())
                                    }">
                                @foreach($customerPrices as $index => $cp)
                                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200 relative group" wire:key="cust-price-{{ $index }}">
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block text-xs font-medium"
                                                    style="color: #6b7280;">Customer</label>

                                                <div x-data="{
                                                                                open: false,
                                                                                search: '',
                                                                                get filteredCustomers() {
                                                                                    if (this.search === '') return this.allCustomers;
                                                                                    return this.allCustomers.filter(c =>
                                                                                        c.name.toLowerCase().includes(this.search.toLowerCase()) ||
                                                                                        c.email.toLowerCase().includes(this.search.toLowerCase())
                                                                                    );
                                                                                },
                                                                                get selectedCustomer() {
                                                                                    return this.allCustomers.find(c => c.id == $wire.customerPrices[{{ $index }}].user_id);
                                                                                }
                                                                            }" @click.outside="open = false" class="relative">

                                                    <!-- Trigger -->
                                                    <button type="button"
                                                        @click="open = !open; if(open) $nextTick(() => $refs.searchInput.focus())"
                                                        class="relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-1.5 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-xs"
                                                        style="color: black;">
                                                        <span class="block truncate"
                                                            x-text="selectedCustomer ? selectedCustomer.name + ' (' + selectedCustomer.email + ')' : 'Select Customer'"></span>
                                                        <span
                                                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                            <svg class="h-4 w-4" style="color: #9ca3af;" viewBox="0 0 20 20"
                                                                fill="none" stroke="currentColor">
                                                                <path d="M7 7l3-3 3 3m0 6l-3 3-3-3" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </span>
                                                    </button>

                                                    <!-- Dropdown -->
                                                    <div x-show="open" x-transition:leave="transition ease-in duration-100"
                                                        x-transition:leave-start="opacity-100"
                                                        x-transition:leave-end="opacity-0"
                                                        class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                                                        style="display: none;">

                                                        <div
                                                            class="sticky top-0 z-10 bg-white px-2 py-1.5 border-b border-gray-100">
                                                            <input x-ref="searchInput" x-model="search" type="text"
                                                                class="block w-full border-0 border-b border-transparent bg-gray-50 focus:border-indigo-500 focus:ring-0 sm:text-xs rounded px-2 py-1"
                                                                style="color: black;" placeholder="Search...">
                                                        </div>

                                                        <ul class="max-h-56 overflow-auto py-1">
                                                            <template x-for="customer in filteredCustomers" :key="customer.id">
                                                                <li @click="$wire.customerPrices[{{ $index }}].user_id = customer.id; open = false; search = '';"
                                                                    class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-50"
                                                                    style="color: black;">
                                                                    <div class="flex flex-col">
                                                                        <span class="font-medium truncate"
                                                                            x-text="customer.name"></span>
                                                                        <span class="text-xs"
                                                                            style="color: #6b7280; font-weight: normal;"
                                                                            x-text="customer.email"></span>
                                                                    </div>
                                                                    <span
                                                                        x-show="$wire.customerPrices[{{ $index }}].user_id == customer.id"
                                                                        class="text-indigo-600 absolute inset-y-0 right-0 flex items-center pr-4">
                                                                        <svg class="h-4 w-4" viewBox="0 0 20 20"
                                                                            fill="currentColor">
                                                                            <path fill-rule="evenodd"
                                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                                clip-rule="evenodd" />
                                                                        </svg>
                                                                    </span>
                                                                </li>
                                                            </template>
                                                            <li x-show="filteredCustomers.length === 0"
                                                                class="text-xs p-3 text-center" style="color: #6b7280;">
                                                                No matches found
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                @error("customerPrices.{$index}.user_id") <span
                                                    class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                            </div>

                                            <div>
                                                <label class="block text-xs font-medium" style="color: #6b7280;">Price (GBP)</label>
                                                <div class="relative rounded shadow-sm mt-1">
                                                    <div class="pointer-events-none absolute inset-y-0 left-0 pl-2 flex items-center">
                                                        <span class="sm:text-xs" style="color: #6b7280;">£</span>
                                                    </div>
                                                    <input type="number" step="0.01"
                                                        wire:model="customerPrices.{{ $index }}.price"
                                                        class="block w-full rounded border-gray-300 text-xs py-1.5 pl-6 px-2 focus:ring-indigo-500 focus:border-indigo-500"
                                                        style="color: black;" placeholder="0.00">
                                                </div>
                                                @error("customerPrices.{$index}.price") <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <button type="button" wire:click="removeCustomerPrice({{ $index }})"
                                            class="absolute top-2 right-2 text-gray-400 hover:text-red-500">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            @if(empty($customerPrices))
                                <div class="text-xs text-center italic py-2" style="color: #6b7280;">No customer prices added.
                                </div>
                            @endif
                        </div>
                    @endif
                    </div>
                </div>
            </div>





        </div>
    </div>

    <!-- SEO Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mt-8">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold" style="color: black;">SEO Optimized</h2>
            <p class="mt-1 text-sm" style="color: #6b7280;">Improve your product's visibility on search engines.
            </p>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <div class="flex justify-between">
                    <label class="block text-sm font-medium" style="color: black;">SEO Title</label>
                    <span class="text-xs" style="color: #6b7280;">{{ strlen($seo_title ?? '') }} / 60</span>
                </div>
                <input type="text" wire:model="seo_title"
                    class="w-full rounded-lg border px-3 py-2 bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-btn-primary-ring"
                    style="color: black;">
            </div>
            <div>
                <div class="flex justify-between">
                    <label class="block text-sm font-medium" style="color: black;">SEO Description</label>
                    <span class="text-xs" style="color: #6b7280;">{{ strlen($seo_description ?? '') }} /
                        160</span>
                </div>
                <textarea wire:model="seo_description" rows="3"
                    class="w-full rounded-lg border px-3 py-2 bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-btn-primary-ring"
                    style="color: black;"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium" style="color: black;">SEO Keywords</label>
                <input type="text" wire:model="seo_keywords" placeholder="Comma separated keywords"
                    class="w-full rounded-lg border px-3 py-2 bg-white border-[#D2D2D2] focus:border-indigo-500 focus:ring-btn-primary-ring"
                    style="color: black;">
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="mt-8 flex justify-end gap-3 border-t border-gray-200 pt-6">
        <a href="{{ route('admin.products.index') }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-black bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring">
            Cancel
        </a>
        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            {{ isset($productId) ? 'Save Changes' : 'Save' }}
        </button>
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