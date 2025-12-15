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
        <!-- Search -->
        <div class="flex items-center gap-3">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search categories..."
                class="w-full md:w-80 rounded-lg border border-[#D2D2D2] px-3 py-2 text-black"
                style="border-color:#D2D2D2; color:#000; placeholder-color:#D2D2D2;">
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border border-zinc-200">
            <table class="min-w-full text-sm">
                <thead style="background-color: #fff;">
                    <tr>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color: #000;">Name</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color: #000;">Slug</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color: #000;">Parent</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color: #000;">SEO Title</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color: #000;">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($categories as $category)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-black">{{ $category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-black">{{ $category->slug }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-black">
                                {{ $category->parent->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-black">
                                {{ Str::limit($category->seo_title, 30) ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium space-x-2 flex items-center">
                                @can('news-categories.edit')
                                    <button wire:click="edit({{ $category->id }})"
                                        class="border border-black px-1.5 py-1 rounded hover:cursor-pointer text-black bg-white flex items-center justify-center"
                                        style="height:32px;width:32px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="color:#000000;" viewBox="0 0 1200 1200">
                                            <path fill="currentColor" d="M0 0v1200h1200V424.292l-196.875 196.875v381.958h-806.25v-806.25h381.958L775.708 0zm1050 0l-76.831 76.831l150 150L1200 150zM936.914 113.086L497.168 552.832l150 150l439.746-439.746zM441.943 622.339c-2.225.034-4.493.195-6.738.366v142.09h142.09c0-38.708-18.492-78.039-47.314-105.542c-23.842-22.751-54.675-37.428-88.038-36.914"></path>
                                        </svg>
                                    </button>
                                @endcan
                                @can('news-categories.delete')
                                    <button wire:confirm="Are you sure you want to delete this category?"
                                        wire:click="delete({{ $category->id }})"
                                        class="border border-black px-1.5 py-1 rounded hover:cursor-pointer text-black bg-white flex items-center justify-center"
                                        style="height:32px;width:32px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="color:#000000;" viewBox="0 0 12 12">
	                                            <path fill="none" stroke="currentColor" stroke-linecap="round" d="M2 2.5h8" stroke-width="1"></path>
	                                            <path fill="currentColor" d="M2 4v7c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4zm3 5.5c0 .28-.22.5-.5.5S4 9.78 4 9.5V6c0-.28.22-.5.5-.5s.5.22.5.5zm3 0c0 .28-.22.5-.5.5S7 9.78 7 9.5V6c0-.28.22-.5.5-.5s.5.22.5.5zM8 3H4V1c0-.55.45-1 1-1h2c.55 0 1 .45 1 1z"></path>
                                        </svg>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-black">No categories found.</td>
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
