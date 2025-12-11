<div class="p-6 space-y-6 dark:bg-zinc-900">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Product Categories</h1>
        @can('product-categories.create')
            <button wire:click="create"
                class="px-4 py-2 bg-zinc-900 text-white rounded-lg hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 transition-colors">
                + New Category
            </button>
        @endcan
    </div>

    @if($showForm)
        <div class="bg-white p-6 rounded-2xl shadow dark:bg-zinc-900 dark:border dark:border-zinc-700">
            <h2 class="text-xl font-semibold mb-4 dark:text-white">{{ $editingId ? 'Edit Category' : 'Create Category' }}
            </h2>

            <form wire:submit.prevent="save" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Name</label>
                        <input type="text" wire:model.live="name"
                            class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Slug</label>
                        <input type="text" wire:model="slug"
                            class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900">
                        @error('slug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Parent Category -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Parent
                            Category</label>
                        <select wire:model="parent_id"
                            class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                            <option value="">None (Top Level)</option>
                            @foreach($parentCandidates as $candidate)
                                <option value="{{ $candidate->id }}">{{ $candidate->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Image -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Image</label>
                        <input type="file" wire:model="image"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:text-zinc-300 dark:file:bg-zinc-700 dark:file:text-zinc-100">
                        @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                        <div wire:loading wire:target="image" class="text-sm text-gray-500 mt-1">Uploading...</div>

                        @if ($image)
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-1 dark:text-zinc-400">Preview:</p>
                                <img src="{{ $image->temporaryUrl() }}"
                                    class="h-32 w-auto object-cover rounded border dark:border-zinc-600">
                            </div>
                        @elseif ($editingId)
                            @php
                                $currentCategory = \App\Models\ProductCategory::find($editingId);
                            @endphp
                            @if($currentCategory && $currentCategory->image_path)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-1 dark:text-zinc-400">Current Image:</p>
                                    <img src="{{ Storage::url($currentCategory->image_path) }}"
                                        class="h-32 w-auto object-cover rounded border dark:border-zinc-600">
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- SEO Section -->
                    <div class="md:col-span-2 border-t pt-4 dark:border-zinc-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">SEO Metadata</h3>

                        <div class="space-y-4">
                            <!-- SEO Title -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">SEO
                                    Title</label>
                                <input type="text" wire:model="seo_title"
                                    class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                                @error('seo_title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- SEO Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">SEO
                                    Description</label>
                                <textarea wire:model="seo_description" rows="3"
                                    class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"></textarea>
                                @error('seo_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- SEO Keywords -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">SEO
                                    Keywords</label>
                                <input type="text" wire:model="seo_keywords"
                                    class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                                    placeholder="comma, separated, keywords">
                                @error('seo_keywords') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" wire:click="cancel"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-zinc-900 text-white rounded-lg hover:bg-zinc-800 dark:bg-white dark:text-zinc-900">Save</button>
                </div>
            </form>
        </div>
    @else
        <!-- Search -->
        <div class="flex items-center gap-3">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search categories..."
                class="w-full md:w-80 rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border dark:border-zinc-700 dark:bg-zinc-800">
            <table class="min-w-full text-sm">
                <thead class="bg-zinc-50 text-left dark:bg-zinc-900 dark:text-zinc-200">
                    <tr>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider">Parent</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider">SEO Title</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse($categories as $category)
                        <tr class="dark:text-zinc-100">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($category->image_path)
                                    <img src="{{ Storage::url($category->image_path) }}" class="h-12 w-auto object-cover rounded">
                                @else
                                    <span class="text-gray-400">No Image</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400">
                                {{ $category->parent?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400">{{ $category->slug }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400">
                                {{ Str::limit($category->seo_title, 30) ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium space-x-2">
                                @can('product-categories.edit')
                                    <button wire:click="edit({{ $category->id }})"
                                        class="px-3 py-1.5 rounded border dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 hover:bg-gray-50 dark:hover:bg-zinc-700">Edit</button>
                                @endcan
                                @can('product-categories.delete')
                                    <button wire:confirm="Are you sure you want to delete this category?"
                                        wire:click="delete({{ $category->id }})"
                                        class="px-3 py-1.5 rounded border text-red-600 dark:border-zinc-600 dark:bg-zinc-800 dark:text-red-400 hover:bg-red-50 dark:hover:bg-zinc-700">Delete</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-zinc-400">No categories found.
                            </td>
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