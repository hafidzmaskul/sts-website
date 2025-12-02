<div class="p-6 space-y-6 dark:bg-zinc-900">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">News Articles</h1>
        @can('news.create')
            <button wire:click="create"
                class="px-4 py-2 bg-zinc-900 text-white rounded-lg hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 transition-colors">
                + New Article
            </button>
        @endcan
    </div>

    @if($showForm)
        <div x-data x-init="$nextTick(() => window.initCKEditor && window.initCKEditor())"
            class="bg-white p-6 rounded-2xl shadow dark:bg-zinc-900 dark:border dark:border-zinc-700">
            <h2 class="text-xl font-semibold mb-4 dark:text-white">{{ $editingId ? 'Edit Article' : 'Create Article' }}</h2>

            <form wire:submit.prevent="save" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Title</label>
                        <input type="text" wire:model.live="title"
                            class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Slug -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Slug</label>
                        <input type="text" wire:model="slug"
                            class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900">
                        @error('slug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Status</label>
                        <select wire:model="status"
                            class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="archived">Archived</option>
                        </select>
                        @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Published At -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Published At</label>
                        <input type="datetime-local" wire:model="published_at"
                            class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                        @error('published_at') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Categories -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Categories</label>
                        <div
                            class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-40 overflow-y-auto border rounded-lg p-3 dark:bg-zinc-800 dark:border-zinc-700">
                            @foreach($categories as $category)
                                <label class="flex items-center gap-2 dark:text-zinc-100">
                                    <input type="checkbox" wire:model="selectedCategories" value="{{ $category->id }}"
                                        class="dark:accent-zinc-700">
                                    <span class="text-sm">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('selectedCategories') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Image -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Featured
                            Image</label>
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
                                $currentNews = \App\Models\News::find($editingId);
                            @endphp
                            @if($currentNews && $currentNews->image_path)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-1 dark:text-zinc-400">Current Image:</p>
                                    <img src="{{ Storage::url($currentNews->image_path) }}"
                                        class="h-32 w-auto object-cover rounded border dark:border-zinc-600">
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Content</label>
                        <input id="overview_input" type="hidden" wire:model.live="content" value="{{ $content ?? '' }}">
                        <div wire:ignore>
                            <div id="overview_editor"
                                class="min-h-[260px] rounded-lg border border-input bg-background dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
                            </div>
                        </div>
                        @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search news..."
                class="w-full md:w-80 rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border dark:border-zinc-700 dark:bg-zinc-800">
            <table class="min-w-full text-sm">
                <thead class="bg-zinc-50 text-left dark:bg-zinc-900 dark:text-zinc-200">
                    <tr>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider">Categories</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider">Published At</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse($news as $article)
                        <tr class="dark:text-zinc-100">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($article->image_path)
                                    <img src="{{ Storage::url($article->image_path) }}" class="h-12 w-auto object-cover rounded">
                                @else
                                    <span class="text-gray-400">No Image</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">{{ Str::limit($article->title, 40) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-xs rounded-full 
                                                                    @if($article->status === 'published') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                                    @elseif($article->status === 'draft') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                    {{ ucfirst($article->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($article->categories as $cat)
                                        <span
                                            class="px-2 py-0.5 text-xs bg-gray-100 rounded dark:bg-zinc-700">{{ $cat->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400">
                                {{ $article->published_at ? $article->published_at->format('M d, Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium space-x-2">
                                @can('news.edit')
                                    <button wire:click="edit({{ $article->id }})"
                                        class="px-3 py-1.5 rounded border dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 hover:bg-gray-50 dark:hover:bg-zinc-700">Edit</button>
                                @endcan
                                @can('news.delete')
                                    <button wire:confirm="Are you sure you want to delete this article?"
                                        wire:click="delete({{ $article->id }})"
                                        class="px-3 py-1.5 rounded border text-red-600 dark:border-zinc-600 dark:bg-zinc-800 dark:text-red-400 hover:bg-red-50 dark:hover:bg-zinc-700">Delete</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-zinc-400">No news articles
                                found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $news->links() }}
        </div>
    @endif
</div>