<div>
    <header class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur dark:border-zinc-700 dark:bg-zinc-800/50">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold dark:text-white">
                General Settings
            </h1>
        </div>
    </header>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="rounded-2xl bg-white p-6 dark:bg-zinc-900 border dark:border-zinc-700">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">
                            Admin Notification Email
                        </label>
                        <p class="text-lg font-semibold dark:text-white truncate">{{ $adminEmail }}</p>
                    </div>
                    @can('settings.update')
                    <button 
                        wire:click="edit('admin_email')" 
                        class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 transition-colors"
                    >
                        Setting
                    </button>
                    @endcan
                </div>
            </div>

            <div class="rounded-2xl bg-white p-6 dark:bg-zinc-900 border dark:border-zinc-700">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">
                            Hero Banner Image
                        </label>
                        @if($heroBanner)
                            <img src="{{ Storage::url($heroBanner) }}" alt="Hero Banner" class="mt-2 h-20 w-auto rounded-lg object-cover">
                        @else
                            <p class="text-lg font-semibold dark:text-white">No image set</p>
                        @endif
                    </div>
                    @can('settings.update')
                    <button 
                        wire:click="edit('hero_banner')" 
                        class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 transition-colors"
                    >
                        Setting
                    </button>
                    @endcan
                </div>
            </div>

        </div>
    </div>

    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="w-full max-w-lg rounded-2xl bg-white p-6 dark:bg-zinc-900">
            <h2 class="text-xl font-semibold mb-4 dark:text-white">{{ $editingLabel }}</h2>

            <form wire:submit.prevent="save" class="space-y-5">
                
                @if($editingKey === 'admin_email')
                <div>
                    <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Value</label>
                    <input
                        type="email"
                        wire:model="editingValue"
                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                    >
                    @error('editingValue') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
                @endif

                @if($editingKey === 'hero_banner')
                <div>
                    <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Upload Image</label>
                    <input
                        type="file"
                        wire:model="heroBannerUpload"
                        class="w-full text-sm dark:text-zinc-100 file:mr-4 file:rounded-lg file:border-0 file:bg-zinc-900 file:px-4 file:py-2 file:text-white file:dark:bg-white file:dark:text-zinc-900"
                    >
                    
                    <div wire:loading wire:target="heroBannerUpload" class="mt-2 text-sm dark:text-zinc-100">Uploading...</div>

                    @if ($heroBannerUpload)
                        <p class="mt-4 text-sm font-medium dark:text-zinc-100">New Image Preview:</p>
                        <img src="{{ $heroBannerUpload->temporaryUrl() }}" class="mt-2 h-32 w-auto rounded-lg object-cover">
                    @endif

                    @error('heroBannerUpload') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
                @endif


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