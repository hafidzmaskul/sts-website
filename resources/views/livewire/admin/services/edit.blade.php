<div>
    <header class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur dark:border-zinc-700 dark:bg-zinc-800/50">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold dark:text-white">
                Edit Service
            </h1>
        </div>
    </header>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full rounded-2xl bg-white p-6 dark:bg-zinc-900 border dark:border-zinc-700">
            <form wire:submit.prevent="save" class="space-y-5">
                
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Name</label>
                    <input
                        type="text"
                        wire:model="name"
                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                    >
                    @error('name') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Short Description -->
                <div>
                    <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Short Description</label>
                    <textarea
                        wire:model="short_description"
                        rows="3"
                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                        placeholder="Brief summary of the service..."
                    ></textarea>
                    @error('short_description') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Content (Rich Editor) -->
                <div>
                    <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Content</label>
                    <!-- Manual Editor implementation to avoid component errors -->
                    <input id="overview_input" type="hidden" wire:model.live="content" value="{{ $content ?? '' }}">
                    <div wire:ignore>
                        <div id="overview_editor" class="min-h-[260px] rounded-lg border border-input bg-background"></div>
                    </div>
                    @error('content') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Sequence -->
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Sequence</label>
                        <input
                            type="number"
                            wire:model="sequence"
                            class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                        >
                        @error('sequence') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Status (Dropdown) -->
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Status</label>
                        <select wire:model="status" class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                            <option value="0">Draft</option>
                            <option value="1">Published</option>
                        </select>
                        @error('status') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Image</label>
                    <input
                        type="file"
                        wire:model="image"
                        class="w-full text-sm dark:text-zinc-100 file:mr-4 file:rounded-lg file:border-0 file:bg-zinc-900 file:px-4 file:py-2 file:text-white file:dark:bg-white file:dark:text-zinc-900"
                    >
                    <div wire:loading wire:target="image" class="mt-2 text-sm dark:text-zinc-100">Uploading...</div>

                    @if ($image && $image->isPreviewable())
                        <p class="mt-4 text-sm font-medium dark:text-zinc-100">New Image Preview:</p>
                        <img src="{{ $image->temporaryUrl() }}" class="mt-2 w-full max-w-sm rounded-lg object-cover">
                    @elseif ($existingImage)
                        <p class="mt-4 text-sm font-medium dark:text-zinc-100">Current Image:</p>
                        <img src="{{ Storage::url($existingImage) }}" class="mt-2 w-full max-w-sm rounded-lg object-cover">
                    @endif

                    @error('image') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end gap-2 border-t dark:border-zinc-700 pt-5">
                    <a
                        href="{{ route('admin.services.index') }}"
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