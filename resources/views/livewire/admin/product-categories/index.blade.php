<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Product Categories</h1>
        @can('product-categories.create')
            <button wire:click="create"
                class="w-full md:w-auto bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 hover:cursor-pointer transition shadow-sm">
                + New Category
            </button>
        @endcan
    </div>

    @if($showForm)
        <div class="p-6 rounded-xl shadow-sm border border-gray-200 bg-white">
            <h2 class="text-xl font-semibold mb-6 text-gray-900 border-b border-gray-100 pb-4">{{ $editingId ? 'Edit Category' : 'Create Category' }}</h2>
            
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
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-50 disabled:text-gray-500"
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
                                class="flex-1 min-w-0 block w-full rounded-none rounded-r-lg border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-50 disabled:text-gray-500"
                                placeholder="electronics"
                                @disabled($isLocked)>
                        </div>
                        @error('slug') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Parent Category -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1 text-gray-900">Parent Category</label>
                        <select wire:model="parent_id"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-50 disabled:text-gray-500"
                            @disabled($isLocked)>
                            <option value="">None (Top Level)</option>
                            @foreach($parentCandidates as $candidate)
                                <option value="{{ $candidate->id }}">{{ $candidate->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_id') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Image (Always Editable) -->
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
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <span class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
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
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            SEO Metadata
                        </h3>
                        <div class="space-y-6 bg-gray-50 p-6 rounded-xl border border-gray-200">
                            <!-- SEO Title -->
                            <div>
                                <label class="block text-sm font-medium mb-1 text-gray-900">SEO Title</label>
                                <input type="text" wire:model="seo_title"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 disabled:text-gray-500"
                                    placeholder="Title for search engines"
                                    @disabled($isLocked)>
                                @error('seo_title') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- SEO Description -->
                            <div>
                                <label class="block text-sm font-medium mb-1 text-gray-900">SEO Description</label>
                                <textarea wire:model="seo_description" rows="3"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 disabled:text-gray-500"
                                    placeholder="Description for search results"
                                    @disabled($isLocked)></textarea>
                                @error('seo_description') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- SEO Keywords -->
                            <div>
                                <label class="block text-sm font-medium mb-1 text-gray-900">SEO Keywords</label>
                                <input type="text" wire:model="seo_keywords"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 disabled:text-gray-500"
                                    placeholder="comma, separated, keywords"
                                    @disabled($isLocked)>
                                @error('seo_keywords') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end space-x-3">
                    <button type="button" wire:click="cancel"
                        class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Save Category
                    </button>
                </div>
            </form>
        </div>
    @else
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Card Header with Search -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex flex-col gap-2">
                <div class="relative w-full max-w-md">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                        </svg>
                    </span>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search categories..."
                        class="block w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 bg-white text-black placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-sm"
                    >
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Parent</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Slug</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">SEO Title</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-50 transition">
                                <!-- Image -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($category->image_path)
                                        <span class="inline-flex rounded-xl bg-gray-100 p-1">
                                            <img src="{{ Storage::url($category->image_path) }}" class="h-10 w-10 object-cover rounded-lg" alt="Category image">
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-50 px-2 py-1 text-xs text-gray-400 font-medium">
                                            No Image
                                        </span>
                                    @endif
                                </td>

                                <!-- Name -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-black">
                                        {{ $category->name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        #{{ $category->id }}
                                    </div>
                                </td>

                                <!-- Parent -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($category->parent)
                                        <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700">
                                            {{ $category->parent->name }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-50 px-2 py-0.5 text-xs text-gray-400 font-medium">
                                            -
                                        </span>
                                    @endif
                                </td>

                                <!-- Slug -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-black">{{ $category->slug }}</div>
                                </td>

                                <!-- SEO Title -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-black">
                                        {{ Str::limit($category->seo_title, 30) ?: '-' }}
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-2">
                                        @can('product-categories.edit')
                                            <button wire:click="edit({{ $category->id }})"
                                                class="inline-flex items-center justify-center rounded-full p-2 bg-white border border-transparent text-blue-600 hover:bg-blue-50 hover:border-blue-200 transition focus:outline-none focus:ring-2 focus:ring-blue-100"
                                                title="Edit"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 1200 1200" fill="none">
                                                    <path fill="currentColor" d="M0 0v1200h1200V424.292l-196.875 196.875v381.958h-806.25v-806.25h381.958L775.708 0zm1050 0l-76.831 76.831l150 150L1200 150zM936.914 113.086L497.168 552.832l150 150l439.746-439.746zM441.943 622.339c-2.225.034-4.493.195-6.738.366v142.09h142.09c0-38.708-18.492-78.039-47.314-105.542c-23.842-22.751-54.675-37.428-88.038-36.914"></path>
                                                </svg>
                                            </button>
                                        @endcan
                                        @can('product-categories.delete')
                                            @if(!in_array($category->slug, ['discontinued', 'most-needed']))
                                                <button wire:confirm="Are you sure you want to delete this category?"
                                                    wire:click="delete({{ $category->id }})"
                                                    class="inline-flex items-center justify-center rounded-full p-2 bg-white border border-transparent text-red-500 hover:bg-red-50 hover:border-red-200 transition focus:outline-none focus:ring-2 focus:ring-red-100"
                                                    title="Delete"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 12 12" fill="none">
                                                        <path fill="none" stroke="currentColor" stroke-linecap="round" d="M2 2.5h8" stroke-width="1"></path>
                                                        <path fill="currentColor" d="M2 4v7c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4zm3 5.5c0 .28-.22.5-.5.5S4 9.78 4 9.5V6c0-.28.22-.5.5-.5s.5.22.5.5zm3 0c0 .28-.22.5-.5.5S7 9.78 7 9.5V6c0-.28.22-.5.5-.5s.5.22.5.5zM8 3H4V1c0-.55.45-1 1-1h2c.55 0 1 .45 1 1z"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10">
                                    <div class="flex flex-col items-center justify-center text-center space-y-3">
                                        <svg class="h-12 w-12 text-gray-200 mb-2" fill="none" viewBox="0 0 48 48" stroke="currentColor">
                                            <rect width="36" height="24" x="6" y="12" fill="currentColor" rx="4" class="text-gray-100"/>
                                            <path stroke="currentColor" stroke-width="2" d="M16 26v-4m8 4v-8m8 8V22"/>
                                        </svg>
                                        <div class="text-lg font-semibold text-gray-500">No Categories Found</div>
                                        <div class="text-sm text-gray-400">Try changing your search or filter to find what you are looking for.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="border-t border-gray-100 bg-gray-50 px-6 py-3 flex justify-end">
                {{ $categories->links() }}
            </div>
        </div>
    @endif
</div>
