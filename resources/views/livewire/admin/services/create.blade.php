<div>
    <header class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold text-black">
                New Service
            </h1>
        </div>
    </header>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full rounded-2xl bg-white p-6 border">
            <form wire:submit.prevent="save" class="space-y-5">

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium mb-1 text-black">Name</label>
                    <input
                        type="text"
                        wire:model="name"
                        class="w-full rounded-lg border px-3 py-2 bg-white text-black border-zinc-300"
                    >
                    @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Short Description -->
                <div>
                    <label class="block text-sm font-medium mb-1 text-black">Short Description</label>
                    <textarea
                        wire:model="short_description"
                        rows="3"
                        class="w-full rounded-lg border px-3 py-2 bg-white text-black border-zinc-300"
                        placeholder="Brief summary of the service..."
                    ></textarea>
                    @error('short_description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Content (Rich Editor) -->
                <div>
                    <label class="block text-sm font-medium mb-1 text-black">Content</label>
                    <input id="overview_input" type="hidden" wire:model.live="content" value="{{ $content ?? '' }}">
                    <div wire:ignore>
                        <div id="overview_editor" class="min-h-[260px] rounded-lg border border-input bg-background"></div>
                    </div>
                    @error('content') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Sequence -->
                    <div>
                        <label class="block text-sm font-medium mb-1 text-black">Sequence</label>
                        <input
                            type="number"
                            wire:model="sequence"
                            class="w-full rounded-lg border px-3 py-2 bg-white text-black border-zinc-300"
                        >
                        @error('sequence') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Status (Dropdown) -->
                    <div>
                        <label class="block text-sm font-medium mb-1 text-black">Status</label>
                        <select wire:model="status" class="w-full rounded-lg border px-3 py-2 bg-white text-black border-zinc-300">
                            <option value="0">Draft</option>
                            <option value="1">Published</option>
                        </select>
                        @error('status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-medium mb-1 text-black">Image</label>
                    <input
                        type="file"
                        wire:model="image"
                        class="w-full text-sm text-black file:mr-4 file:rounded-lg file:border-0 file:bg-zinc-900 file:px-4 file:py-2 file:text-white"
                    >
                    <div wire:loading wire:target="image" class="mt-2 text-sm text-black">Uploading...</div>

                    @if ($image && $image->isPreviewable())
                        <p class="mt-4 text-sm font-medium text-black">New Image Preview:</p>
                        <img src="{{ $image->temporaryUrl() }}" class="mt-2 w-full max-w-sm rounded-lg object-cover">
                    @endif

                    @error('image') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end gap-2 border-t pt-5">
                    <a
                        href="{{ route('admin.services.index') }}"
                        wire:navigate
                        class="px-4 py-2 rounded-lg border bg-white text-black"
                    >Cancel</a>
                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg bg-zinc-900 text-white transition-colors"
                    >Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
