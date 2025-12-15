<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold" style="color:#000;">News Articles</h1>
        @can('news.create')
            <button wire:click="create"
                class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition">
                + New Article
            </button>
        @endcan
    </div>

    @if($showForm)
        <div x-data x-init="$nextTick(() => window.initCKEditor && window.initCKEditor())"
            class="p-6 rounded-2xl shadow border">
            <h2 class="text-xl font-semibold mb-4" style="color:#000;">{{ $editingId ? 'Edit Article' : 'Create Article' }}</h2>
            <form wire:submit.prevent="save" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1" style="color:#000;">Title</label>
                        <input
                            type="text"
                            wire:model.live="title"
                            class="w-full rounded-lg border px-3 py-2"
                            style="border: 1px solid #AEAEAE; background: none; color: #AEAEAE; placeholder-color: #D2D2D2;"
                            placeholder="Title"
                        >
                        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Slug -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1" style="color:#000;">Slug</label>
                        <input type="text" wire:model="slug"
                            class="w-full rounded-lg border px-3 py-2"
                            style="border: 1px solid #D2D2D2; color: #000; background: none;"
                            placeholder="Slug">
                        @error('slug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#000;">Status</label>
                        <select wire:model="status"
                            class="w-full rounded-lg border px-3 py-2"
                            style="border: 1px solid #D2D2D2; color: #000;">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="archived">Archived</option>
                        </select>
                        @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Published At -->
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#000;">Published At</label>
                        <input type="datetime-local" wire:model="published_at"
                            class="w-full rounded-lg border px-3 py-2"
                            style="border: 1px solid #D2D2D2; color: #000;">
                        @error('published_at') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Categories -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1" style="color:#000;">Categories</label>
                        <div
                            class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-40 overflow-y-auto border rounded-lg p-3"
                            style="border:1px solid #D2D2D2;">
                            @foreach($categories as $category)
                                <label class="flex items-center gap-2" style="color:#000;">
                                    <input type="checkbox" wire:model="selectedCategories" value="{{ $category->id }}">
                                    <span class="text-sm">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('selectedCategories') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Image -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1" style="color:#000;">Featured Image</label>
                        <input type="file" wire:model="image"
                            class="block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            style="color:#000;">
                        @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                        <div wire:loading wire:target="image" class="text-sm mt-1">Uploading...</div>

                        @if ($image)
                            <div class="mt-2">
                                <p class="text-sm mb-1" style="color:#D2D2D2;">Preview:</p>
                                <img src="{{ $image->temporaryUrl() }}"
                                    class="h-32 w-auto object-cover rounded border">
                            </div>
                        @elseif ($editingId)
                            @php
                                $currentNews = \App\Models\News::find($editingId);
                            @endphp
                            @if($currentNews && $currentNews->image_path)
                                <div class="mt-2">
                                    <p class="text-sm mb-1" style="color:#D2D2D2;">Current Image:</p>
                                    <img src="{{ Storage::url($currentNews->image_path) }}"
                                        class="h-32 w-auto object-cover rounded border">
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1" style="color:#000;">Content</label>
                        <input id="overview_input" type="hidden" wire:model.live="content" value="{{ $content ?? '' }}">
                        <div wire:ignore>
                            <div id="overview_editor"
                                class="min-h-[260px] rounded-lg border"
                                style="border:1px solid #D2D2D2; color:#000;">
                            </div>
                        </div>
                        @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- SEO Section -->
                    <div class="md:col-span-2 border-t pt-4" style="border-top:1px solid #D2D2D2;">
                        <h3 class="text-lg font-medium mb-3" style="color:#000;">SEO Metadata</h3>

                        <div class="space-y-4">
                            <!-- SEO Title -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color:#000;">SEO Title</label>
                                <input type="text" wire:model="seo_title"
                                    class="w-full rounded-lg border px-3 py-2"
                                    style="border: 1px solid #D2D2D2; color: #000;"
                                    placeholder="SEO Title">
                                @error('seo_title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- SEO Description -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color:#000;">SEO Description</label>
                                <textarea wire:model="seo_description" rows="3"
                                    class="w-full rounded-lg border px-3 py-2"
                                    style="border:1px solid #D2D2D2; color:#000;"
                                    placeholder="SEO Description"></textarea>
                                @error('seo_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- SEO Keywords -->
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color:#000;">SEO Keywords</label>
                                <input type="text" wire:model="seo_keywords"
                                    class="w-full rounded-lg border px-3 py-2"
                                    style="border: 1px solid #D2D2D2; color: #000;"
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
                    <button type="submit"
                        class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition">
                        Save
                    </button>
                </div>
            </form>
        </div>
    @else
        <!-- Search -->
        <div class="flex items-center gap-3">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search news..."
                class="w-full md:w-80 rounded-lg border px-3 py-2"
                style="border:1px solid #D2D2D2; color:#000; placeholder-color: #D2D2D2;">
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border">
            <table class="min-w-full text-sm">
                <thead class="text-left">
                    <tr>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color:#AEAEAE;">Image</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color:#AEAEAE;">Title</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color:#AEAEAE;">Status</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color:#AEAEAE;">Categories</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color:#AEAEAE;">Published At</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color:#AEAEAE;">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($news as $article)
                        <tr style="color:#000;">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($article->image_path)
                                    <img src="{{ Storage::url($article->image_path) }}" class="h-12 w-auto object-cover rounded">
                                @else
                                    <span style="color:#D2D2D2;">No Image</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">{{ Str::limit($article->title, 40) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-xs rounded-full
                                        @if($article->status === 'published') bg-green-100 text-green-800
                                        @elseif($article->status === 'draft') bg-gray-100 text-gray-800
                                        @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($article->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap" style="color:#000;">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($article->categories as $cat)
                                        <span
                                            class="px-2 py-0.5 text-xs bg-gray-100 rounded">{{ $cat->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap" style="color:#000;">
                                {{ $article->published_at ? $article->published_at->format('M d, Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium space-x-2 flex items-center">
                                @can('news.edit')
                                    <button wire:click="edit({{ $article->id }})"
                                        class="border border-[#AEAEAE] bg-white rounded p-1 flex items-center justify-center"
                                        style="width:28px;height:28px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" style="color:#000000;" viewBox="0 0 1200 1200"><path fill="currentColor" d="M0 0v1200h1200V424.292l-196.875 196.875v381.958h-806.25v-806.25h381.958L775.708 0zm1050 0l-76.831 76.831l150 150L1200 150zM936.914 113.086L497.168 552.832l150 150l439.746-439.746zM441.943 622.339c-2.225.034-4.493.195-6.738.366v142.09h142.09c0-38.708-18.492-78.039-47.314-105.542c-23.842-22.751-54.675-37.428-88.038-36.914"></path></svg>
                                    </button>
                                @endcan
                                @can('news.delete')
                                    <button wire:confirm="Are you sure you want to delete this article?"
                                        wire:click="delete({{ $article->id }})"
                                        class="border border-[#AEAEAE] bg-white rounded p-1 flex items-center justify-center"
                                        style="width:28px;height:28px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" style="color:#000000;" viewBox="0 0 12 12">
                                            <path fill="none" stroke="currentColor" stroke-linecap="round" d="M2 2.5h8" stroke-width="1"></path>
                                            <path fill="currentColor" d="M2 4v7c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4zm3 5.5c0 .28-.22.5-.5.5S4 9.78 4 9.5V6c0-.28.22-.5.5-.5s.5.22.5.5zm3 0c0 .28-.22.5-.5.5S7 9.78 7 9.5V6c0-.28.22-.5.5-.5s.5.22.5.5zM8 3H4V1c0-.55.45-1 1-1h2c.55 0 1 .45 1 1z"></path>
                                        </svg>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center" style="color:#D2D2D2;">No news articles found.</td>
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
