<div class="p-6 space-y-6">

    {{-- ── Page Header ── --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Product Categories</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage your product category hierarchy</p>
        </div>
        @can('product-categories.create')
            <button wire:click="create"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white bg-btn-primary hover:bg-btn-primary-hover shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Parent Category
            </button>
        @endcan
    </div>

    {{-- ── Form Panel ── --}}
    @if($showForm)
        <div class="p-6 rounded-xl shadow-sm border border-gray-200 bg-white">
            <h2 class="text-xl font-semibold mb-6 text-gray-900 border-b border-gray-100 pb-4">
                {{ $editingId ? 'Edit Category' : 'Create Category' }}
            </h2>

            @php
                $isLocked = false;
                if($editingId) {
                    $currentCat = \App\Models\ProductCategory::find($editingId);
                    if($currentCat && in_array($currentCat->slug, ['discontinued', 'most-needed'])) {
                        $isLocked = true;
                    }
                }
            @endphp

            <form wire:submit.prevent="save" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-900">Name</label>
                        <input type="text" wire:model.live="name"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-btn-primary-ring disabled:bg-gray-50 disabled:text-gray-500"
                            placeholder="e.g. Electronics"
                            @disabled($isLocked)>
                        @error('name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-900">Slug</label>
                        <div class="flex rounded-lg shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                /category/
                            </span>
                            <input type="text" wire:model="slug"
                                class="flex-1 min-w-0 block w-full rounded-none rounded-r-lg border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-btn-primary-ring disabled:bg-gray-50 disabled:text-gray-500"
                                placeholder="electronics"
                                @disabled($isLocked)>
                        </div>
                        @error('slug') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Image -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1 text-gray-900">Category Image</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition-colors relative bg-gray-50">
                            <div class="space-y-1 text-center">
                                @if ($image)
                                    <div class="relative inline-block">
                                        <img src="{{ $image->temporaryUrl() }}" class="mx-auto h-48 object-contain rounded-lg shadow-sm">
                                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 opacity-0 hover:opacity-100 transition-opacity rounded-lg text-white font-medium text-sm">Change Image</div>
                                    </div>
                                @elseif ($editingId && ($currentCategory = \App\Models\ProductCategory::find($editingId)) && $currentCategory->image_path)
                                    <div class="relative inline-block">
                                        <img src="{{ Storage::url($currentCategory->image_path) }}" class="mx-auto h-48 object-contain rounded-lg shadow-sm">
                                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 opacity-0 hover:opacity-100 transition-opacity rounded-lg text-white font-medium text-sm">Change Image</div>
                                    </div>
                                @else
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <span class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                            <span>Upload a file</span>
                                        </span>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                @endif
                                <input type="file" wire:model="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                        </div>
                        <div wire:loading wire:target="image" class="text-sm text-indigo-600 mt-2 font-medium">Uploading image...</div>
                        @error('image') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- SEO Section -->
                    <div class="md:col-span-2 pt-6">
                        <h3 class="text-lg font-medium mb-4 text-gray-900 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            SEO Metadata
                        </h3>
                        <div class="space-y-6 bg-gray-50 p-6 rounded-xl border border-gray-200">
                            <div>
                                <label class="block text-sm font-medium mb-1 text-gray-900">SEO Title</label>
                                <input type="text" wire:model="seo_title"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-btn-primary-ring disabled:bg-gray-100 disabled:text-gray-500"
                                    placeholder="Title for search engines" @disabled($isLocked)>
                                @error('seo_title') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1 text-gray-900">SEO Description</label>
                                <textarea wire:model="seo_description" rows="3"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-btn-primary-ring disabled:bg-gray-100 disabled:text-gray-500"
                                    placeholder="Description for search results" @disabled($isLocked)></textarea>
                                @error('seo_description') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1 text-gray-900">SEO Keywords</label>
                                <input type="text" wire:model="seo_keywords"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-btn-primary-ring disabled:bg-gray-100 disabled:text-gray-500"
                                    placeholder="comma, separated, keywords" @disabled($isLocked)>
                                @error('seo_keywords') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-3">
                    <button type="button" wire:click="cancel"
                        class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 border border-transparent text-sm font-semibold rounded-lg shadow-sm text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Category
                    </button>
                </div>
            </form>
        </div>

    @else
        {{-- ── List Card ── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- Search bar --}}
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3 bg-gray-50/60">
                <div class="relative flex-1 max-w-sm">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search categories…"
                        class="w-full pl-9 pr-4 py-2 text-sm rounded-lg border border-gray-200 bg-white text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 transition"
                    >
                </div>
                @if($search)
                    <button wire:click="$set('search', '')" class="text-xs text-gray-400 hover:text-gray-600 transition">
                        Clear
                    </button>
                @endif
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/80">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-full">
                                Category
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @php
                            $parentIndex = ($categories->currentPage() - 1) * $categories->perPage() + 1;
                        @endphp

                        @forelse($categories as $category)
                            @if($isSearchActive)
                                {{-- ── Search result row ── --}}
                                <tr class="hover:bg-gray-50/70 transition-colors group">
                                    <td class="px-6 py-3">
                                        <div class="flex items-center gap-3">
                                            {{-- Avatar --}}
                                            @if($category->image_path)
                                                <img src="{{ Storage::url($category->image_path) }}" class="h-9 w-9 rounded-xl object-cover ring-1 ring-gray-200 shrink-0" alt="{{ $category->name }}">
                                            @else
                                                <span class="inline-flex items-center justify-center h-9 w-9 rounded-xl bg-indigo-50 text-indigo-600 font-bold text-sm shrink-0 ring-1 ring-indigo-100 select-none">
                                                    {{ strtoupper(mb_substr($category->name, 0, 1)) }}
                                                </span>
                                            @endif
                                            {{-- Name --}}
                                            <div>
                                                <div class="flex items-center gap-1.5">
                                                    @if($category->parent)
                                                        <span class="text-gray-300 text-xs">└</span>
                                                    @endif
                                                    <span class="font-medium text-gray-900">{{ $category->name }}</span>
                                                    @if($category->parent)
                                                        <span class="text-xs text-gray-400">· {{ $category->parent->name }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5">
                                            @can('product-categories.create')
                                                <button wire:click="createSubCategory({{ $category->id }})"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-lg border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 hover:border-gray-300 transition-colors"
                                                    title="Add Sub Category">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                                    Sub
                                                </button>
                                            @endcan
                                            @can('product-categories.edit')
                                                <button wire:click="edit({{ $category->id }})"
                                                    class="p-1.5 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </button>
                                            @endcan
                                            @can('product-categories.delete')
                                                @if(!in_array($category->slug, ['discontinued', 'most-needed']))
                                                    <button wire:confirm="Are you sure you want to delete this category?" wire:click="delete({{ $category->id }})"
                                                        class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>

                            @else
                                {{-- ── Parent category row ── --}}
                                <tr class="hover:bg-blue-50/30 transition-colors group border-l-4 border-l-indigo-400">
                                    <td class="px-6 py-3">
                                        <div class="flex items-center gap-3">
                                            @if($category->children->isNotEmpty())
                                                <button type="button" wire:click="toggleCategory({{ $category->id }})" 
                                                    class="text-gray-400 hover:text-indigo-600 focus:outline-none shrink-0" 
                                                    title="{{ in_array($category->id, $expandedCategories) ? 'Collapse' : 'Expand' }}">
                                                    <svg class="w-4 h-4 transform transition-transform duration-200 {{ in_array($category->id, $expandedCategories) ? 'rotate-90' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </button>
                                            @else
                                                <div class="w-4 h-4 shrink-0"></div>
                                            @endif

                                            @if($category->image_path)
                                                <img src="{{ Storage::url($category->image_path) }}" class="h-10 w-10 rounded-xl object-cover ring-2 ring-indigo-100 shrink-0" alt="{{ $category->name }}">
                                            @else
                                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-indigo-100 text-indigo-700 font-bold text-base shrink-0 ring-2 ring-indigo-200 select-none">
                                                    {{ strtoupper(mb_substr($category->name, 0, 1)) }}
                                                </span>
                                            @endif
                                            <div>
                                                <div class="font-semibold text-gray-900 text-sm">{{ $category->name }}</div>
                                                @if($category->children->isNotEmpty())
                                                    <div class="text-xs text-gray-400 mt-0.5">{{ $category->children->count() }} {{ Str::plural('sub-category', $category->children->count()) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5">
                                            @can('product-categories.create')
                                                <button wire:click="createSubCategory({{ $category->id }})"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-lg border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 hover:border-gray-300 transition-colors"
                                                    title="Add Sub Category">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                                    Sub
                                                </button>
                                            @endcan
                                            @can('product-categories.edit')
                                                <button wire:click="edit({{ $category->id }})"
                                                    class="p-1.5 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </button>
                                            @endcan
                                            @can('product-categories.delete')
                                                @if(!in_array($category->slug, ['discontinued', 'most-needed']))
                                                    <button wire:confirm="Are you sure you want to delete this category?" wire:click="delete({{ $category->id }})"
                                                        class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>

                                {{-- Children --}}
                                @if($category->children->isNotEmpty() && in_array($category->id, $expandedCategories))
                                    <x-product-category-children :children="$category->children" :depth="1" :expanded-categories="$expandedCategories" />
                                @endif
                            @endif

                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-16">
                                    <div class="flex flex-col items-center justify-center text-center gap-3">
                                        <div class="h-14 w-14 rounded-2xl bg-gray-100 flex items-center justify-center">
                                            <svg class="h-7 w-7 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7h18M3 12h18M3 17h18"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-base font-semibold text-gray-500">No Categories Found</div>
                                            <div class="text-sm text-gray-400 mt-0.5">
                                                @if($search)
                                                    No results for "{{ $search }}". Try a different search.
                                                @else
                                                    Get started by creating your first parent category.
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="border-t border-gray-100 bg-gray-50/60 px-6 py-3 flex justify-end overflow-x-auto">
            {{ $categories->links() }}
        </div>
        </div>
    @endif

</div>
