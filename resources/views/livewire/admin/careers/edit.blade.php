<div>
    <header class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur dark:border-zinc-700 dark:bg-zinc-800/50">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold dark:text-white">
                Edit Job Posting
            </h1>
        </div>
    </header>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full rounded-2xl bg-white p-6 dark:bg-zinc-900 border dark:border-zinc-700">
            <form wire:submit.prevent="save" class="space-y-5">
                
                <div>
                    <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Job Title</label>
                    <input type="text" wire:model="title" class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                    @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Level</label>
                        <input type="text" wire:model="level" class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Employment Type</label>
                        <input type="text" wire:model="employment_type" class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Department</label>
                        <input type="text" wire:model="department" class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Location</label>
                        <input type="text" wire:model="location" class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                    </div>
                </div>

                <!-- Description (Standard Textarea) -->
                <div>
                    <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Description</label>
                    <textarea
                        wire:model="description"
                        rows="10"
                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                    ></textarea>
                    @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Sequence</label>
                        <input type="number" wire:model="sequence" class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Status</label>
                        <select wire:model="status" class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                            <option value="0">Draft</option>
                            <option value="1">Published</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 border-t dark:border-zinc-700 pt-5">
                    <a href="{{ route('admin.careers.index') }}" wire:navigate class="px-4 py-2 rounded-lg border dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">Cancel</a>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 transition-colors">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>