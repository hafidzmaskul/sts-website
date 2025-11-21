<div>
    <header class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur dark:border-zinc-700 dark:bg-zinc-800/50">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold dark:text-white">
                Testimonials
            </h1>
            @can('testimonials.create')
            <button 
                wire:click="create" 
                class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 transition-colors"
            >
                New Testimonial
            </button>
            @endcan
        </div>
    </header>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-12 sm:px-6 lg:px-8">
        
        <div class="flex items-center gap-3">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Search by name or job title..."
                class="w-full md:w-80 rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
            />
        </div>

        <div class="overflow-x-auto rounded-xl border dark:border-zinc-700 dark:bg-zinc-800">
            <table class="min-w-full text-sm">
                <thead class="bg-zinc-50 text-left dark:bg-zinc-900 dark:text-zinc-200">
                    <tr>
                        <th class="px-4 py-3">Image</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Job Title</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Sequence</th>
                        <th class="px-4 py-3 w-40">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonials as $testimonial)
                        <tr class="border-t dark:border-zinc-700">
                            <td class="px-4 py-3">
                                @if($testimonial->image)
                                    <img src="{{ Storage::url($testimonial->image) }}" alt="{{ $testimonial->name }}" class="h-10 w-10 rounded-full object-cover">
                                @else
                                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-zinc-200 dark:bg-zinc-700">
                                        ?
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium dark:text-white">{{ $testimonial->name }}</td>
                            <td class="px-4 py-3 dark:text-zinc-300">{{ $testimonial->job_title }}</td>
                            <td class="px-4 py-3">
                                @if($testimonial->status)
                                    <span class="px-2 py-0.5 rounded border text-xs text-green-700 border-green-400 bg-green-100 dark:bg-green-900 dark:border-green-700 dark:text-green-200">
                                        Published
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded border text-xs text-zinc-600 border-zinc-400 bg-zinc-100 dark:bg-zinc-700 dark:border-zinc-600 dark:text-zinc-300">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 dark:text-zinc-300">{{ $testimonial->sequence }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    @can('testimonials.edit')
                                    <button
                                        wire:click="edit({{ $testimonial->id }})"
                                        class="px-3 py-1.5 rounded border dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                                    >Edit</button>
                                    @endcan
                                    @can('testimonials.delete')
                                    <button
                                        wire:click="delete({{ $testimonial->id }})"
                                        wire:confirm="Are you sure you want to delete this testimonial?"
                                        class="px-3 py-1.5 rounded border text-red-600 dark:border-zinc-600 dark:bg-zinc-800 dark:text-red-400"
                                    >Delete</button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-zinc-500 dark:text-zinc-400">No testimonials found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $testimonials->links() }}</div>

        @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="w-full max-w-3xl rounded-2xl bg-white p-6 dark:bg-zinc-900 max-h-[90vh] overflow-y-auto">
                <h2 class="text-xl font-semibold mb-4 dark:text-white">{{ $editingId ? 'Edit Testimonial' : 'New Testimonial' }}</h2>

                <form wire:submit.prevent="save" class="space-y-5">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
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
                            <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Job Title</label>
                            <input
                                type="text"
                                wire:model="job_title"
                                class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                            >
                            @error('job_title') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Description</label>
                        <textarea
                            wire:model="description"
                            rows="4"
                            class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                        ></textarea>
                        @error('description') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Sequence</label>
                            <input
                                type="number"
                                wire:model="sequence"
                                class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                            >
                            @error('sequence') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        
                        <!-- UPDATED STATUS FIELD (Dropdown) -->
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Status</label>
                            <select wire:model="status" class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                                <option value="0">Draft</option>
                                <option value="1">Published</option>
                            </select>
                            @error('status') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
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
                            <img src="{{ $image->temporaryUrl() }}" class="mt-2 h-20 w-20 rounded-full object-cover">
                        @elseif ($existingImage)
                            <p class="mt-4 text-sm font-medium dark:text-zinc-100">Current Image:</p>
                            <img src="{{ Storage::url($existingImage) }}" class="mt-2 h-20 w-20 rounded-full object-cover">
                        @endif

                        @error('image') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
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