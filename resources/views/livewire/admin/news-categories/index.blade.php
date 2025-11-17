<div>
    <header class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur dark:border-zinc-700 dark:bg-zinc-800/50">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold dark:text-white">
                News Categories
            </h1>
            @can('news-categories.create')
            <button 
                wire:click="create" 
                class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 transition-colors"
            >
                New Category
            </button>
            @endcan
        </div>
    </header>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-12 sm:px-6 lg:px-8">
        
        <div class="flex items-center gap-3">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Search by name..."
                class="w-full md:w-80 rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
            />
        </div>

        <div class="overflow-x-auto rounded-xl border dark:border-zinc-700 dark:bg-zinc-800">
            <table class="min-w-full text-sm">
                <thead class="bg-zinc-50 text-left dark:bg-zinc-900 dark:text-zinc-200">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Slug</th>
                        <th class="px-4 py-3">Parent Category</th>
                        <th class="px-4 py-3 w-40">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr class="border-t dark:border-zinc-700">
                            <td class="px-4 py-3 font-medium dark:text-white">{{ $category->name }}</td>
                            <td class="px-4 py-3 dark:text-zinc-300">{{ $category->slug }}</td>
                            <td class="px-4 py-3 dark:text-zinc-300">
                                {{ $category->parent?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    @can('news-categories.edit')
                                    <button
                                        wire:click="edit({{ $category->id }})"
                                        class="px-3 py-1.5 rounded border dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                                    >Edit</button>
                                    @endcan
                                    @can('news-categories.delete')
                                    <button
                                        wire:click="delete({{ $category->id }})"
                                        wire:confirm="Are you sure you want to delete this category?"
                                        class="px-3 py-1.5 rounded border text-red-600 dark:border-zinc-600 dark:bg-zinc-800 dark:text-red-400"
                                    >Delete</button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-zinc-500 dark:text-zinc-400">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $categories->links() }}</div>

        @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="w-full max-w-xl rounded-2xl bg-white p-6 dark:bg-zinc-900 max-h-[90vh] overflow-y-auto">
                <h2 class="text-xl font-semibold mb-4 dark:text-white">{{ $editingId ? 'Edit Category' : 'New Category' }}</h2>

                <form wire:submit.prevent="save" class="space-y-5">
                    
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Name</label>
                        <input
                            type="text"
                            wire:model="name"
                            class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                        >
                        @error('name') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Parent Category</label>
                        <select
                            wire:model="parent_id"
                            class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                        >
                            <option value="">— None —</option>
                            @foreach($allCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_id') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    

                    <div class="flex items-center justify-end gap-2">
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="px-4 py-2 rounded-lg border dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                        >Cancel</button>
                        <button
                            type="submit"
                            class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 transition-colors"
                        >Save</button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>