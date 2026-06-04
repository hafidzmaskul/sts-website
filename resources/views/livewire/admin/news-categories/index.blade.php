<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-black">News Categories</h1>
        @can('news-categories.create')
            <button wire:click="create" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            New Category
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
                            style="border-color: #D2D2D2; color:#000;"
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
                            style="border-color: #D2D2D2; color:#000;"
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
                            style="border-color: #D2D2D2; color:#000;">
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
                                    class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black"
                                    style="border-color: #D2D2D2; color: #000;"
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
                                    style="border-color: #D2D2D2; color:#000;"
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
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Save
        </button>
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
                        class="block w-full pl-10 pr-3 py-2 rounded-lg border border-gray-200 text-black placeholder-[#888] bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition text-sm"
                    />
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left uppercase text-xs font-semibold tracking-wider text-black">Name</th>
                            <th class="px-6 py-3 text-left uppercase text-xs font-semibold tracking-wider text-black">Slug</th>
                            <th class="px-6 py-3 text-left uppercase text-xs font-semibold tracking-wider text-black">Parent</th>
                            <th class="px-6 py-3 text-left uppercase text-xs font-semibold tracking-wider text-black">SEO Title</th>
                            <th class="px-6 py-3 text-right uppercase text-xs font-semibold tracking-wider text-black">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-50 transition">
                                <!-- Name -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-black">{{ $category->name }}</div>
                                    @if($category->status ?? null)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-medium mt-1">
                                            <span class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                            Active
                                        </span>
                                    @endif
                                </td>
                                <!-- Slug -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs text-black">{{ $category->slug }}</div>
                                </td>
                                <!-- Parent -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($category->parent)
                                        <span class="inline-flex px-2 py-0.5 rounded bg-gray-100 text-black text-xs font-medium">{{ $category->parent->name }}</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded bg-gray-50 text-[#888] text-xs">-</span>
                                    @endif
                                </td>
                                <!-- SEO Title -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($category->seo_title)
                                        <div class="text-xs text-black">{{ \Illuminate\Support\Str::limit($category->seo_title, 30) }}</div>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded bg-gray-50 text-[#bbb] text-xs">-</span>
                                    @endif
                                </td>
                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-right">
<div class="flex items-center justify-end gap-2">
<div class="flex justify-end gap-2">
                                        @can('news-categories.edit')
                                            <button wire:click="edit({{ $category->id }})" class="text-black hover:text-blue-600 transition-colors inline-flex" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                        @endcan
                                        @can('news-categories.delete')
                                            <button wire:confirm="Are you sure you want to delete this category?" wire:click="delete({{ $category->id }})" class="text-black hover:text-red-600 transition-colors inline-flex" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                        @endcan
                                    </div>
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
                                        <div class="text-lg font-semibold text-black mb-1">No categories found</div>
                                        <div class="text-sm text-[#888]">Try adjusting your search or create a new category.</div>
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
