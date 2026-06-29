<div class="p-6 space-y-6 text-black">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">News Articles</h1>
        @can('news.create')
            <button wire:click="create" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            New Article
        </button>
        @endcan
    </div>

    @if($showForm)
        <div x-data x-init="$nextTick(() => window.initCKEditor && window.initCKEditor())"
            class="p-6 rounded-2xl shadow border">
            <h2 class="text-xl font-semibold mb-4">{{ $editingId ? 'Edit Article' : 'Create Article' }}</h2>
            <form wire:submit.prevent="save" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Title</label>
                        <input
                            type="text"
                            wire:model.live="title"
                            class="w-full rounded-lg border px-3 py-2 text-black"
                            style="border: 1px solid #AEAEAE; background: none; color: #000;"
                            placeholder="Title"
                        >
                        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Slug -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Slug</label>
                        <input type="text" wire:model="slug"
                            class="w-full rounded-lg border px-3 py-2 text-black"
                            style="border: 1px solid #D2D2D2; background: none;"
                            placeholder="Slug">
                        @error('slug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select wire:model="status"
                            class="w-full rounded-lg border px-3 py-2 text-black"
                            style="border: 1px solid #D2D2D2;">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="archived">Archived</option>
                        </select>
                        @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Published At -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Published At</label>
                        <input type="datetime-local" wire:model="published_at"
                            class="w-full rounded-lg border px-3 py-2 text-black"
                            style="border: 1px solid #D2D2D2;">
                        @error('published_at') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Categories -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Categories</label>
                        <div
                            class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-40 overflow-y-auto border rounded-lg p-3 text-black"
                            style="border:1px solid #D2D2D2;">
                            @foreach($categories as $category)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" wire:model="selectedCategories" value="{{ $category->id }}">
                                    <span class="text-sm">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('selectedCategories') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Image -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Featured Image</label>
                        <input type="file" wire:model="image"
                            class="block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 text-black">
                        @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                        <div wire:loading wire:target="image" class="text-sm mt-1">Uploading...</div>

                        @if ($image)
                            <div class="mt-2">
                                <p class="text-sm mb-1 text-gray-400">Preview:</p>
                                <img src="{{ $image->temporaryUrl() }}"
                                    class="h-32 w-auto object-cover rounded border">
                            </div>
                        @elseif ($editingId)
                            @php
                                $currentNews = \App\Models\News::find($editingId);
                            @endphp
                            @if($currentNews && $currentNews->image_path)
                                <div class="mt-2">
                                    <p class="text-sm mb-1 text-gray-400">Current Image:</p>
                                    <img src="{{ Storage::url($currentNews->image_path) }}"
                                        class="h-32 w-auto object-cover rounded border">
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Content</label>
                        <input id="overview_input" type="hidden" wire:model.live="content" value="{{ $content ?? '' }}">
                        <div wire:ignore>
                            <div id="overview_editor"
                                class="min-h-[260px] rounded-lg border text-black"
                                style="border:1px solid #D2D2D2;">
                            </div>
                        </div>
                        @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- SEO Section -->
                    <div class="md:col-span-2 border-t pt-4" style="border-top:1px solid #D2D2D2;">
                        <h3 class="text-lg font-medium mb-3">SEO Metadata</h3>

                        <div class="space-y-4">
                            <!-- SEO Title -->
                            <div>
                                <label class="block text-sm font-medium mb-1">SEO Title</label>
                                <input type="text" wire:model="seo_title"
                                    class="w-full rounded-lg border px-3 py-2 text-black"
                                    style="border: 1px solid #D2D2D2;"
                                    placeholder="SEO Title">
                                @error('seo_title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- SEO Description -->
                            <div>
                                <label class="block text-sm font-medium mb-1">SEO Description</label>
                                <textarea wire:model="seo_description" rows="3"
                                    class="w-full rounded-lg border px-3 py-2 text-black"
                                    style="border:1px solid #D2D2D2;"
                                    placeholder="SEO Description"></textarea>
                                @error('seo_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- SEO Keywords -->
                            <div>
                                <label class="block text-sm font-medium mb-1">SEO Keywords</label>
                                <input type="text" wire:model="seo_keywords"
                                    class="w-full rounded-lg border px-3 py-2 text-black"
                                    style="border: 1px solid #D2D2D2;"
                                    placeholder="comma, separated, keywords">
                                @error('seo_keywords') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" wire:click="cancel"
                        class="px-4 py-2 border border-[#0079C2] text-[#0079C2] rounded-lg hover:cursor-pointer transition">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Save
        </button>
                </div>
            </form>
        </div>
    @else
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Card Header - Search -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <div class="relative w-full max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="gray" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-4.35-4.35M5 11a6 6 0 1112 0 6 6 0 01-12 0z"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search news..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 bg-white text-black placeholder-gray-400 transition"
                    >
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y text-sm text-black">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs uppercase tracking-wider font-semibold">Image</th>
                            <th class="px-6 py-3 text-xs uppercase tracking-wider font-semibold">Title</th>
                            <th class="px-6 py-3 text-xs uppercase tracking-wider font-semibold">Status</th>
                            <th class="px-6 py-3 text-xs uppercase tracking-wider font-semibold">Categories</th>
                            <th class="px-6 py-3 text-xs uppercase tracking-wider font-semibold">Published At</th>
                            <th class="px-6 py-3 text-xs uppercase tracking-wider font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($news as $article)
                            <tr class="hover:bg-gray-50 transition">
                                <!-- Image -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($article->image_path)
                                        <img src="{{ Storage::url($article->image_path) }}" alt="" class="h-12 w-12 object-cover rounded-lg bg-gray-100" />
                                    @else
                                        <span class="inline-flex items-center h-12 w-12 justify-center rounded-lg bg-gray-100 text-gray-400 text-xs">
                                            <svg class="w-6 h-6" fill="none" stroke="gray" stroke-width="1.5" viewBox="0 0 24 24">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.5A2.5 2.5 0 015.5 3h13A2.5 2.5 0 0121 5.5v13a2.5 2.5 0 01-2.5 2.5h-13A2.5 2.5 0 013 18.5v-13z" />
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M3 17.5l7-7a2.121 2.121 0 012.828 0l8.672 8.672m-14.5-1.172v1.5a.5.5 0 00.5.5h1.5M15 11.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                            </svg>
                                        </span>
                                    @endif
                                </td>
                                <!-- Title -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium">
                                        {{ Str::limit($article->title, 40) }}
                                    </div>
                                    {{-- Optional subtitle/info --}}
                                    {{-- <div class="text-xs text-gray-500">{{ $article->slug }}</div> --}}
                                </td>
                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-medium
                                        @if($article->status === 'published') bg-green-100
                                        @elseif($article->status === 'draft') bg-gray-100
                                        @else bg-red-100 @endif text-black">
                                        <span class="inline-block w-2 h-2 rounded-full mr-1
                                            @if($article->status === 'published') bg-green-500
                                            @elseif($article->status === 'draft') bg-gray-400
                                            @else bg-red-500 @endif">
                                        </span>
                                        {{ ucfirst($article->status) }}
                                    </span>
                                </td>
                                <!-- Categories -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($article->categories as $cat)
                                            <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 rounded text-xs font-medium">
                                                {{ $cat->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <!-- Published At -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium">
                                        {{ $article->published_at ? $article->published_at->format('M d, Y H:i') : '-' }}
                                    </div>
                                    {{-- <div class="text-xs text-gray-500">Additional info</div> --}}
                                </td>
                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-right">
<div class="flex items-center justify-end gap-2">
<div class="flex justify-end gap-2">
                                        @can('news.edit')
                                            <button wire:click="edit({{ $article->id }})" class="text-black hover:text-blue-600 transition-colors inline-flex" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                        @endcan
                                        @can('news.delete')
                                            <button wire:confirm="Are you sure you want to delete this article?" wire:click="delete({{ $article->id }})" class="text-black hover:text-red-600 transition-colors inline-flex" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                        @endcan
                                    </div>
</div>
</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="flex flex-col items-center justify-center py-16">
                                        <svg class="w-16 h-16 mb-4 text-gray-400" fill="none" stroke="gray" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 8v4l3 3m7 1a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div class="text-lg font-semibold mb-1">No news articles found</div>
                                        <div class="text-sm">Try adjusting your search or filter to find results.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="px-6 py-3 border-t bg-gray-50 rounded-b-xl overflow-x-auto">
            {{ $news->links() }}
        </div>
        </div>
    @endif
</div>
