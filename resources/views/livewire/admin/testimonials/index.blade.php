<div>
    <header class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold text-black">
                Testimonials
            </h1>
            @can('testimonials.create')
            <button wire:click="create" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
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
                class="w-full md:w-80 rounded-lg border px-3 py-2 text-black bg-white border-zinc-200"
            />
        </div>

        <div class="overflow-x-auto rounded-xl border bg-white border-zinc-200">
            <table class="min-w-full text-sm text-black">
                <thead class="bg-zinc-50 text-left">
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
                        <tr class="border-t border-zinc-200">
                            <td class="px-4 py-3">
                                @if($testimonial->image)
                                    <img src="{{ Storage::url($testimonial->image) }}" alt="{{ $testimonial->name }}" class="h-10 w-10 rounded-full object-cover">
                                @else
                                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-zinc-200">
                                        ?
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium text-black">{{ $testimonial->name }}</td>
                            <td class="px-4 py-3 text-black">{{ $testimonial->job_title }}</td>
                            <td class="px-4 py-3">
                                @if($testimonial->status)
                                    <span class="px-2 py-0.5 rounded border text-xs text-green-700 border-green-400 bg-green-100">
                                        Published
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded border text-xs text-zinc-600 border-zinc-400 bg-zinc-100">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-black">{{ $testimonial->sequence }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    @can('testimonials.edit')
                                    <button wire:click="edit({{ $testimonial->id }})" class="text-black hover:text-blue-600 transition-colors inline-flex" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    @endcan
                                    @can('testimonials.delete')
                                    <button wire:confirm="Are you sure you want to delete this testimonial?" wire:click="delete({{ $testimonial->id }})" class="text-black hover:text-red-600 transition-colors inline-flex" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-zinc-500">No testimonials found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $testimonials->links() }}</div>

        @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="w-full max-w-3xl rounded-2xl bg-white p-6 max-h-[90vh] overflow-y-auto">
                <h2 class="text-xl font-semibold mb-4 text-black">{{ $editingId ? 'Edit Testimonial' : 'New Testimonial' }}</h2>

                <form wire:submit.prevent="save" class="space-y-5 text-black">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium mb-1 text-black">Name</label>
                            <input
                                type="text"
                                wire:model="name"
                                class="w-full rounded-lg border px-3 py-2 text-black bg-white border-zinc-200"
                            >
                            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 text-black">Job Title</label>
                            <input
                                type="text"
                                wire:model="job_title"
                                class="w-full rounded-lg border px-3 py-2 text-black bg-white border-zinc-200"
                            >
                            @error('job_title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1 text-black">Description</label>
                        <textarea
                            wire:model="description"
                            rows="4"
                            class="w-full rounded-lg border px-3 py-2 text-black bg-white border-zinc-200"
                        ></textarea>
                        @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium mb-1 text-black">Sequence</label>
                            <input
                                type="number"
                                wire:model="sequence"
                                class="w-full rounded-lg border px-3 py-2 text-black bg-white border-zinc-200"
                            >
                            @error('sequence') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- UPDATED STATUS FIELD (Dropdown) -->
                        <div>
                            <label class="block text-sm font-medium mb-1 text-black">Status</label>
                            <select wire:model="status" class="w-full rounded-lg border px-3 py-2 text-black bg-white border-zinc-200">
                                <option value="0">Draft</option>
                                <option value="1">Published</option>
                            </select>
                            @error('status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1 text-black">Image</label>
                        <input
                            type="file"
                            wire:model="image"
                            class="w-full text-sm text-black file:mr-4 file:rounded-lg file:border-0 file:bg-zinc-900 file:px-4 file:py-2 file:text-white"
                        >
                        <div wire:loading wire:target="image" class="mt-2 text-sm text-black">Uploading...</div>

                        @if ($image)
                            <p class="mt-4 text-sm font-medium text-black">New Image Preview:</p>
                            <img src="{{ $image->temporaryUrl() }}" class="mt-2 h-20 w-20 rounded-full object-cover">
                        @elseif ($existingImage)
                            <p class="mt-4 text-sm font-medium text-black">Current Image:</p>
                            <img src="{{ Storage::url($existingImage) }}" class="mt-2 h-20 w-20 rounded-full object-cover">
                        @endif

                        @error('image') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>


                    <div class="flex items-center justify-end gap-2">
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="px-4 py-2 rounded-lg border border-zinc-200 bg-white text-black"
                        >Cancel</button>
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Save
        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
