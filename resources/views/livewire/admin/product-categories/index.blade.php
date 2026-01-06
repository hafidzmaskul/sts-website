<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-black">Product Categories</h1>
        @can('product-categories.create')
            <button wire:click="create"
                class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition">
                + New Category
            </button>
        @endcan
    </div>

    @if($showForm)
        <div class="p-6 rounded-2xl shadow border">
            <h2 class="text-xl font-semibold mb-4 text-black">{{ $editingId ? 'Edit Category' : 'Create Category' }}</h2>
            <form wire:submit.prevent="save" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-500">Name</label>
                        <input type="text" wire:model.live="name"
                            class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2]"
                            placeholder="Name">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-500">Slug</label>
                        <input type="text" wire:model="slug"
                            class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2]"
                            placeholder="Slug">
                        @error('slug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Parent Category -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1 text-gray-500">Parent Category</label>
                        <select wire:model="parent_id"
                            class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2]">
                            <option value="">None (Top Level)</option>
                            @foreach($parentCandidates as $candidate)
                                <option value="{{ $candidate->id }}">{{ $candidate->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Image -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1 text-gray-500">Image</label>
                        <input type="file" wire:model="image"
                            class="block w-full text-sm text-black file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                        <div wire:loading wire:target="image" class="text-sm text-gray-500 mt-1">Uploading...</div>

                        @if ($image)
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-1">Preview:</p>
                                <img src="{{ $image->temporaryUrl() }}"
                                    class="h-32 w-auto object-cover rounded border">
                            </div>
                        @elseif ($editingId)
                            @php
                                $currentCategory = \App\Models\ProductCategory::find($editingId);
                            @endphp
                            @if($currentCategory && $currentCategory->image_path)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-1">Current Image:</p>
                                    <img src="{{ Storage::url($currentCategory->image_path) }}"
                                        class="h-32 w-auto object-cover rounded border">
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- SEO Section -->
                    <div class="md:col-span-2 border-t pt-4">
                        <h3 class="text-lg font-medium mb-3 text-black">SEO Metadata</h3>
                        <div class="space-y-4">
                            <!-- SEO Title -->
                            <div>
                                <label class="block text-sm font-medium mb-1 text-gray-500">SEO Title</label>
                                <input type="text" wire:model="seo_title"
                                    class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2]"
                                    placeholder="SEO Title">
                                @error('seo_title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- SEO Description -->
                            <div>
                                <label class="block text-sm font-medium mb-1 text-gray-500">SEO Description</label>
                                <textarea wire:model="seo_description" rows="3"
                                    class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2]"
                                    placeholder="SEO Description"></textarea>
                                @error('seo_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- SEO Keywords -->
                            <div>
                                <label class="block text-sm font-medium mb-1 text-gray-500">SEO Keywords</label>
                                <input type="text" wire:model="seo_keywords"
                                    class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2]"
                                    placeholder="comma, separated, keywords">
                                @error('seo_keywords') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" wire:click="cancel"
                        class="w-full md:w-auto border border-[#0079C2] text-[#0079C2] bg-white px-4 py-2 rounded-lg hover:cursor-pointer transition">Cancel</button>
                    <button type="submit"
                        class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition">Save</button>
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
