<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-black">News Categories</h1>
        @can('news-categories.create')
            <button wire:click="create"
                class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition">
                + New Category
            </button>
        @endcan
    </div>

    @if($showForm)
        <div class="p-6 rounded-2xl shadow border border-zinc-200">
            <h2 class="text-xl font-semibold mb-4 text-black">{{ $editingId ? 'Edit Category' : 'Create Category' }}
            </h2>

            <form wire:submit.prevent="save" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-black mb-1">Name</label>
                        <input
                            type="text"
                            wire:model.live="name"
                            class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black"
                            placeholder="Enter category name"
                            style="border-color: #D2D2D2; color:#000; placeholder-color:#D2D2D2;"
                        >
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label class="block text-sm font-medium text-black mb-1">Slug</label>
                        <input
                            type="text"
                            wire:model="slug"
                            class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black"
                            style="border-color: #D2D2D2; color:#000; placeholder-color:#D2D2D2;"
                            placeholder="Enter slug"
                        >
                        @error('slug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Parent Category -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-black mb-1">Parent Category</label>
                        <select
                            wire:model="parent_id"
                            class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black"
                            style="border-color: #D2D2D2; color:#000; placeholder-color:#D2D2D2;">
                            <option value="">None (Top Level)</option>
                            @foreach($parentOptions as $option)
                                <option value="{{ $option->id }}">{{ $option->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- SEO Section -->
                    <div class="md:col-span-2 border-t pt-4 border-zinc-200">
                        <h3 class="text-lg font-medium text-black mb-3">SEO Metadata</h3>
                        <div class="space-y-4">
                            <!-- SEO Title -->
                            <div>
                                <label class="block text-sm font-medium text-black mb-1">SEO Title</label>
                                <input
                                    type="text"
                                    wire:model="seo_title"
                                    class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2"
                                    style="border-color: #D2D2D2; color: #000; placeholder-color:#D2D2D2;"
                                    placeholder="SEO Title"
                                >
                                @error('seo_title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- SEO Description -->
                            <div>
                                <label class="block text-sm font-medium text-black mb-1">SEO Description</label>
                                <textarea
                                    wire:model="seo_description"
                                    rows="3"
                                    class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black"
                                    style="border-color: #D2D2D2; color:#000; placeholder-color:#D2D2D2;"
                                    placeholder="SEO Description"
                                ></textarea>
                                @error('seo_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- SEO Keywords -->
                            <div>
                                <label class="block text-sm font-medium text-black mb-1">SEO Keywords</label>
                                <input
                                    type="text"
                                    wire:model="seo_keywords"
                                    class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black"
                                    style="border-color: #D2D2D2; color:#000;"
                                    placeholder="comma, separated, keywords"
                                >
                                @error('seo_keywords') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" wire:click="cancel"
                        class="w-full md:w-auto border border-[#0079C2] px-4 py-2 rounded-lg text-[#0079C2] hover:cursor-pointer transition bg-white">Cancel</button>
                    <button type="submit"
                        class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition">Save</button>
                </div>
            </form>
        </div>
    @else
        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Card Header with Search -->
            <div class="px-4 py-4 border-b border-gray-100 bg-gray-50 flex items-center">
                <div class="relative w-full md:w-80">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21 21l-4.35-4.35M18 11A7 7 0 1 1 4 11a7 7 0 0 1 14 0z"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search categories..."
                        class="block w-full pl-10 pr-3 py-2 rounded-lg border border-gray-200 text-black placeholder-gray-400 bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition text-sm"
                    />
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left uppercase text-xs font-semibold tracking-wider text-gray-700">Name</th>
                            <th class="px-6 py-3 text-left uppercase text-xs font-semibold tracking-wider text-gray-700">Slug</th>
                            <th class="px-6 py-3 text-left uppercase text-xs font-semibold tracking-wider text-gray-700">Parent</th>
                            <th class="px-6 py-3 text-left uppercase text-xs font-semibold tracking-wider text-gray-700">SEO Title</th>
                            <th class="px-6 py-3 text-right uppercase text-xs font-semibold tracking-wider text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-50 transition">
                                <!-- Name -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                    @if($category->status ?? null)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-medium mt-1">
                                            <span class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                            Active
                                        </span>
                                    @endif
                                </td>
                                <!-- Slug -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs text-gray-700">{{ $category->slug }}</div>
                                </td>
                                <!-- Parent -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($category->parent)
                                        <span class="inline-flex px-2 py-0.5 rounded bg-gray-100 text-gray-800 text-xs font-medium">{{ $category->parent->name }}</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded bg-gray-50 text-gray-500 text-xs">-</span>
                                    @endif
                                </td>
                                <!-- SEO Title -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($category->seo_title)
                                        <div class="text-xs text-gray-700">{{ \Illuminate\Support\Str::limit($category->seo_title, 30) }}</div>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded bg-gray-50 text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-2">
                                        @can('news-categories.edit')
                                            <button wire:click="edit({{ $category->id }})"
                                                class="p-2 rounded hover:bg-blue-50 text-gray-600 hover:text-blue-600 transition"
                                                aria-label="Edit"
                                                title="Edit"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 20 20" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                          d="M17.414 2.586a2 2 0 0 1 0 2.828l-9.193 9.193-3.515.439.438-3.515 9.192-9.193a2 2 0 0 1 2.83 0zm0 0L15 5m-7 12h10"/>
                                                </svg>
                                            </button>
                                        @endcan
                                        @can('news-categories.delete')
                                            <button
                                                wire:confirm="Are you sure you want to delete this category?"
                                                wire:click="delete({{ $category->id }})"
                                                class="p-2 rounded hover:bg-red-50 text-gray-600 hover:text-red-600 transition"
                                                aria-label="Delete"
                                                title="Delete"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 20 20" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M6 8v8m4-8v8m4-10v10m-5-4h4m-1-4h5m-7-4h6.5a1 1 0 0 1 1 1V5m2 0a1 1 0 0 1 1 1v1M12.73 5l1.364-1.364a2 2 0 1 0-2.828-2.828L9.9 2.172"/>
                                                </svg>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 48 48">
                                            <rect x="6" y="12" width="36" height="26" rx="3" fill="#F3F4F6"></rect>
                                            <path d="M6 37c5-8 11.5-8 16 0s11.5 8 16 0" stroke="#D1D5DB" stroke-width="2" />
                                            <circle cx="24" cy="22" r="6" fill="#E5E7EB" />
                                        </svg>
                                        <div class="text-lg font-semibold text-gray-700 mb-1">No categories found</div>
                                        <div class="text-sm text-gray-500">Try adjusting your search or create a new category.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="border-t border-gray-100 px-4 py-3 bg-gray-50">
                {{ $categories->links() }}
            </div>
        </div>
    @endif
</div>
