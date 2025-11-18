<div>
    <header class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur dark:border-zinc-700 dark:bg-zinc-800/50">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold dark:text-white">
                New Post
            </h1>
        </div>
    </header>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full rounded-2xl bg-white p-6 dark:bg-zinc-900 border dark:border-zinc-700">
            <form wire:submit.prevent="save" class="space-y-5">
                        
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="md:col-span-2 space-y-5">
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Title</label>
                            <input
                                type="text"
                                wire:model="title"
                                class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                            >
                            @error('title') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Content</label>
                            <textarea
                                wire:model="content"
                                rows="12"
                                class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                            ></textarea>
                            @error('content') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="p-4 rounded-lg border dark:border-zinc-700 dark:bg-zinc-800">
                            <h3 class="text-lg font-medium mb-2 dark:text-white">SEO Meta Data</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Meta Title</label>
                                    <input
                                        type="text"
                                        wire:model="meta_title"
                                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-900 dark:text-white dark:border-zinc-700"
                                    >
                                    @error('meta_title') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Meta Description</label>
                                    <textarea
                                        wire:model="meta_description"
                                        rows="2"
                                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-900 dark:text-white dark:border-zinc-700"
                                    ></textarea>
                                    @error('meta_description') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Meta Keywords</label>
                                    <input
                                        type="text"
                                        wire:model="meta_keyword"
                                        placeholder="e.g., tech, laravel, news"
                                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-900 dark:text-white dark:border-zinc-700"
                                    >
                                    @error('meta_keyword') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="md:col-span-1 space-y-5">
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Status</label>
                            <select wire:model="status" class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="archived">Archived</option>
                            </select>
                            @error('status') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Categories</label>
                            <div class="grid grid-cols-1 gap-2 max-h-48 overflow-y-auto border rounded-lg p-3 dark:bg-zinc-800 dark:border-zinc-700">
                                @forelse($allCategories as $category)
                                    <label class="flex items-center gap-2 dark:text-zinc-100">
                                        <input
                                            type="checkbox"
                                            wire:model="selectedCategories"
                                            value="{{ $category->id }}"
                                            class="dark:accent-zinc-700"
                                        />
                                        <span class="text-sm">{{ $category->name }}</span>
                                    </label>
                                @empty
                                    <span class="text-sm text-zinc-500">No categories created yet.</span>
                                @endforelse
                            </div>
                            @error('selectedCategories') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Image</label>
                            <input
                                type="file"
                                wire:model="image"
                                class="w-full text-sm dark:text-zinc-100 file:mr-4 file:rounded-lg file:border-0 file:bg-zinc-900 file:px-4 file:py-2 file:text-white file:dark:bg-white file:dark:text-zinc-900"
                            >
                            <div wire:loading wire:target="image" class="mt-2 text-sm dark:text-zinc-100">Uploading...</div>

                            @if ($image)
                                <p class="mt-4 text-sm font-medium dark:text-zinc-100">New Image Preview:</p>
                                <img src="{{ $image->temporaryUrl() }}" class="mt-2 w-full rounded-lg object-cover">
                            @endif

                            @error('image') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 border-t dark:border-zinc-700 pt-5">
                    <a
                        href="{{ route('admin.news.index') }}"
                        wire:navigate
                        class="px-4 py-2 rounded-lg border dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                    >Cancel</a>
                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 transition-colors"
                    >Save</button>
                </div>
            </form>
        </div>
    </div>
</div>