<div>
    <header class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur dark:border-zinc-700 dark:bg-zinc-800/50">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold dark:text-white">
                News
            </h1>
            @can('news.create')
            <a 
                href="{{ route('admin.news.create') }}" 
                wire:navigate
                class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 transition-colors"
            >
                New Post
            </a>
            @endcan
        </div>
    </header>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-12 sm:px-6 lg:px-8">
        
        <div class="flex items-center gap-3">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Search by title..."
                class="w-full md:w-80 rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
            />
        </div>

        <div class="overflow-x-auto rounded-xl border dark:border-zinc-700 dark:bg-zinc-800">
            <table class="min-w-full text-sm">
                <thead class="bg-zinc-50 text-left dark:bg-zinc-900 dark:text-zinc-200">
                    <tr>
                        <th class="px-4 py-3">Image</th>
                        <th class="px-4 py-3">Title</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Categories</th>
                        <th class="px-4 py-3">Author</th>
                        <th class="px-4 py-3 w-40">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($news as $post)
                        <tr class="border-t dark:border-zinc-700">
                            <td class="px-4 py-3">
                                @if($post->image)
                                    <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="h-10 w-16 rounded object-cover">
                                @else
                                    <span class="flex h-10 w-16 items-center justify-center rounded bg-zinc-200 dark:bg-zinc-700">
                                        ?
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium dark:text-white">{{ $post->title }}</td>
                            <td class="px-4 py-3">
                                @if($post->status == 'published')
                                    <span class="px-2 py-0.5 rounded border text-xs text-green-700 border-green-400 bg-green-100 dark:bg-green-900 dark:border-green-700 dark:text-green-200">
                                        Published
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded border text-xs text-zinc-600 border-zinc-400 bg-zinc-100 dark:bg-zinc-700 dark:border-zinc-600 dark:text-zinc-300">
                                        {{ ucfirst($post->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 dark:text-zinc-300 text-xs">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($post->categories as $category)
                                        <span class="px-2 py-0.5 rounded border dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100">{{ $category->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3 dark:text-zinc-300">{{ $post->user->name }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    @can('news.edit')
                                    <a
                                        href="{{ route('admin.news.edit', $post) }}"
                                        wire:navigate
                                        class="px-3 py-1.5 rounded border dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                                    >Edit</a>
                                    @endcan
                                    @can('news.delete')
                                    <button
                                        wire:click="delete({{ $post->id }})"
                                        wire:confirm="Are you sure you want to delete this post?"
                                        class="px-3 py-1.5 rounded border text-red-600 dark:border-zinc-600 dark:bg-zinc-800 dark:text-red-400"
                                    >Delete</button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-zinc-500 dark:text-zinc-400">No news posts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $news->links() }}</div>

    </div>
</div>