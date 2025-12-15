<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-[#000000]">Product Categories</h1>
        @can('product-categories.create')
            <button wire:click="create"
                class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition">
                + New Category
            </button>
        @endcan
    </div>

    @if($showForm)
        <div class="p-6 rounded-2xl shadow border">
            <h2 class="text-xl font-semibold mb-4 text-[#000000]">{{ $editingId ? 'Edit Category' : 'Create Category' }}
            </h2>
            <form wire:submit.prevent="save" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">Name</label>
                        <input type="text" wire:model.live="name"
                            class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2]"
                            placeholder="Name">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">Slug</label>
                        <input type="text" wire:model="slug"
                            class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2]"
                            placeholder="Slug">
                        @error('slug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Parent Category -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">Parent Category</label>
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
                        <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">Image</label>
                        <input type="file" wire:model="image"
                            class="block w-full text-sm text-[#000000] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
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
                        <h3 class="text-lg font-medium mb-3" style="color: #000000">SEO Metadata</h3>
                        <div class="space-y-4">
                            <!-- SEO Title -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">SEO Title</label>
                                <input type="text" wire:model="seo_title"
                                    class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2]"
                                    placeholder="SEO Title">
                                @error('seo_title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- SEO Description -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">SEO Description</label>
                                <textarea wire:model="seo_description" rows="3"
                                    class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2]"
                                    placeholder="SEO Description"></textarea>
                                @error('seo_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- SEO Keywords -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">SEO Keywords</label>
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
        <!-- Search -->
        <div class="flex items-center gap-3">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search categories..."
                class="w-full md:w-80 rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2]">
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border">
            <table class="min-w-full text-sm">
                <thead style="background-color:#fff;">
                    <tr>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color: #AEAEAE">Image</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color: #AEAEAE">Name</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color: #AEAEAE">Parent</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color: #AEAEAE">Slug</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color: #AEAEAE">SEO Title</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color: #AEAEAE">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($categories as $category)
                        <tr class="">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($category->image_path)
                                    <img src="{{ Storage::url($category->image_path) }}" class="h-12 w-auto object-cover rounded">
                                @else
                                    <span class="text-gray-400">No Image</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-black">{{ $category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-black">
                                {{ $category->parent?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-black">{{ $category->slug }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-black">
                                {{ Str::limit($category->seo_title, 30) ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium space-x-2 flex items-center">
                                @can('product-categories.edit')
                                    <button wire:click="edit({{ $category->id }})"
                                        class="border border-[#0079C2] text-[#0079C2] bg-white p-1 rounded-lg hover:cursor-pointer transition flex items-center justify-center"
                                        style="width:28px;height:28px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 1200 1200" style="color:#000000;display:inline-block;">
                                            <path fill="currentColor" d="M0 0v1200h1200V424.292l-196.875 196.875v381.958h-806.25v-806.25h381.958L775.708 0zm1050 0l-76.831 76.831l150 150L1200 150zM936.914 113.086L497.168 552.832l150 150l439.746-439.746zM441.943 622.339c-2.225.034-4.493.195-6.738.366v142.09h142.09c0-38.708-18.492-78.039-47.314-105.542c-23.842-22.751-54.675-37.428-88.038-36.914"></path>
                                        </svg>
                                    </button>
                                @endcan
                                @can('product-categories.delete')
                                    <button wire:confirm="Are you sure you want to delete this category?"
                                        wire:click="delete({{ $category->id }})"
                                        class="border border-[#0079C2] text-[#0079C2] bg-white p-1 rounded-lg hover:cursor-pointer transition flex items-center justify-center"
                                        style="width:28px;height:28px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 12 12" style="color:#000000;display:inline-block;">
                                            <path fill="none" stroke="currentColor" stroke-linecap="round" d="M2 2.5h8" stroke-width="1"></path>
                                            <path fill="currentColor" d="M2 4v7c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4zm3 5.5c0 .28-.22.5-.5.5S4 9.78 4 9.5V6c0-.28.22-.5.5-.5s.5.22.5.5zm3 0c0 .28-.22.5-.5.5S7 9.78 7 9.5V6c0-.28.22-.5.5-.5s.5.22.5.5zM8 3H4V1c0-.55.45-1 1-1h2c.55 0 1 .45 1 1z"></path>
                                        </svg>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    @endif
</div>
