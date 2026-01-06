<div>
    <header class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold text-black">
                Edit Job Posting
            </h1>
        </div>
    </header>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full rounded-2xl bg-white p-6 border">
            <form wire:submit.prevent="save" class="space-y-5">

                <div>
                    <label class="block text-sm font-medium mb-1 text-black">Job Title</label>
                    <input type="text" wire:model="title" class="w-full rounded-lg border px-3 py-2 bg-white text-black border-zinc-300">
                    @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium mb-1 text-black">Level</label>
                        <input type="text" wire:model="level" class="w-full rounded-lg border px-3 py-2 bg-white text-black border-zinc-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 text-black">Employment Type</label>
                        <input type="text" wire:model="employment_type" class="w-full rounded-lg border px-3 py-2 bg-white text-black border-zinc-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 text-black">Department</label>
                        <input type="text" wire:model="department" class="w-full rounded-lg border px-3 py-2 bg-white text-black border-zinc-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 text-black">Location</label>
                        <input type="text" wire:model="location" class="w-full rounded-lg border px-3 py-2 bg-white text-black border-zinc-300">
                    </div>
                </div>

                <!-- Description (Standard Textarea) -->
                <div>
                    <label class="block text-sm font-medium mb-1 text-black">Content</label>
                    <!-- Manual Editor implementation to avoid component errors -->
                    <input id="overview_input" type="hidden" wire:model.live="content" value="{{ $content ?? '' }}">
                    <div wire:ignore>
                        <div id="overview_editor" class="min-h-[260px] rounded-lg border border-input bg-background"></div>
                    </div>
                    @error('content') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium mb-1 text-black">Sequence</label>
                        <input type="number" wire:model="sequence" class="w-full rounded-lg border px-3 py-2 bg-white text-black border-zinc-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1 text-black">Status</label>
                        <select wire:model="status" class="w-full rounded-lg border px-3 py-2 bg-white text-black border-zinc-300">
                            <option value="0">Draft</option>
                            <option value="1">Published</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 border-t pt-5">
                    <a href="{{ route('admin.careers.index') }}" wire:navigate class="px-4 py-2 rounded-lg border text-black bg-white">Cancel</a>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-zinc-900 text-white transition-colors">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
