<div>
    <header class="sticky top-0 z-10 border-b border-zinc-200 backdrop-blur">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold text-black">
                New Post
            </h1>
        </div>
    </header>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full rounded-2xl border p-6">
            <form wire:submit.prevent="save" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="md:col-span-2 space-y-5">
                        <div>
                            <label class="block text-sm font-medium mb-1 text-black">Title</label>
                            <input
                                type="text"
                                wire:model="title"
                                style="color: #000; border-color: #D2D2D2;"
                                class="w-full rounded-lg border px-3 py-2 placeholder-[#D2D2D2] text-black"
                                placeholder="Title"
                            >
                            @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 text-black">Content</label>
                            <input id="overview_input" type="hidden" wire:model.live="content" value="{{ $content ?? '' }}">
                            <div wire:ignore>
                                <div id="overview_editor" class="min-h-[260px] rounded-lg border border-[#D2D2D2]"></div>
                            </div>
                            @error('content') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="p-4 rounded-lg border">
                            <h3 class="text-lg font-medium mb-2 text-black">SEO Meta Data</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1 text-black">Meta Title</label>
                                    <input
                                        type="text"
                                        wire:model="meta_title"
                                        class="w-full rounded-lg border px-3 py-2 border-[#D2D2D2] placeholder-[#D2D2D2] text-black"
                                        placeholder="Meta Title"
                                    >
                                    @error('meta_title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 text-black">Meta Description</label>
                                    <textarea
                                        wire:model="meta_description"
                                        rows="2"
                                        class="w-full rounded-lg border px-3 py-2 border-[#D2D2D2] placeholder-[#D2D2D2] text-black"
                                        placeholder="Meta Description"
                                    ></textarea>
                                    @error('meta_description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 text-black">Meta Keywords</label>
                                    <input
                                        type="text"
                                        wire:model="meta_keyword"
                                        placeholder="e.g., tech, laravel, news"
                                        class="w-full rounded-lg border px-3 py-2 border-[#D2D2D2] placeholder-[#D2D2D2] text-black"
                                    >
                                    @error('meta_keyword') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-1 space-y-5">
                        <div>
                            <label class="block text-sm font-medium mb-1 text-black">Status</label>
                            <select wire:model="status" class="w-full rounded-lg border px-3 py-2 border-[#D2D2D2] text-black placeholder-[#D2D2D2]">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="archived">Archived</option>
                            </select>
                            @error('status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 text-black">Categories</label>
                            <div class="grid grid-cols-1 gap-2 max-h-48 overflow-y-auto border rounded-lg p-3">
                                @forelse($allCategories as $category)
                                    <label class="flex items-center gap-2 text-black">
                                        <input
                                            type="checkbox"
                                            wire:model="selectedCategories"
                                            value="{{ $category->id }}"
                                        />
                                        <span class="text-sm">{{ $category->name }}</span>
                                    </label>
                                @empty
                                    <span class="text-sm text-zinc-500">No categories created yet.</span>
                                @endforelse
                            </div>
                            @error('selectedCategories') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 text-black">Image</label>
                            <input
                                type="file"
                                wire:model="image"
                                class="w-full text-sm text-black file:mr-4 file:rounded-lg file:border-0 file:bg-transparent file:px-4 file:py-2 file:text-black"
                            >
                            <div wire:loading wire:target="image" class="mt-2 text-sm text-black">Uploading...</div>

                            @if ($image)
                                <p class="mt-4 text-sm font-medium text-black">New Image Preview:</p>
                                <img src="{{ $image->temporaryUrl() }}" class="mt-2 w-full rounded-lg object-cover">
                            @endif

                            @error('image') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 border-t pt-5">
                    <a
                        href="{{ route('admin.news.index') }}"
                        wire:navigate
                        class="w-full md:w-auto border border-[#0079C2] text-[#0079C2] px-4 py-2 rounded-lg hover:cursor-pointer transition"
                    >Cancel</a>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Save
        </button>
                </div>
            </form>
        </div>
    </div>
</div>
