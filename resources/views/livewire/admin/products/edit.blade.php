<div>
    <header class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur dark:border-zinc-700 dark:bg-zinc-800/50">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold dark:text-white">
                Edit Product
            </h1>
        </div>
    </header>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full rounded-2xl bg-white p-6 dark:bg-zinc-900 border dark:border-zinc-700">
            <form wire:submit.prevent="save" class="space-y-5">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="md:col-span-2 space-y-5">
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
                            <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Content</label>
                            <textarea
                                wire:model="content"
                                rows="8"
                                class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                            ></textarea>
                            @error('content') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="md:col-span-1 space-y-5">
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Price</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm dark:text-zinc-400">£</span>
                                <input
                                    type="number"
                                    wire:model="price"
                                    step="0.01"
                                    class="w-full rounded-lg border py-2 pl-9 pr-3 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                                >
                            </div>
                            @error('price') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Status</label>
                                <label class="flex items-center gap-2 rounded-lg border p-3 dark:bg-zinc-800 dark:border-zinc-700">
                                <input
                                    type="checkbox"
                                    wire:model="status"
                                    class="dark:accent-zinc-700"
                                />
                                <span class="text-sm dark:text-zinc-100">{{ $status ? 'Published' : 'Draft' }}</span>
                            </label>
                            @error('status') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Image</label>
                            <input
                                type="file"
                                wire:model="image"
                                class="w-full text-sm dark:text-zinc-100 file:mr-4 file:rounded-lg file:border-0 file:bg-zinc-900 file:px-4 file:py-2 file:text-white file:dark:bg-white file:dark:text-zinc-900"
                            >
                            <div wire:loading wire:target="image" class="mt-2 text-sm dark:text-zinc-100">Uploading...</div>
                            
                            @if ($image && $image->isPreviewable())
                                <img src="{{ $image->temporaryUrl() }}" class="mt-4 w-full rounded-lg object-cover">
                            @elseif ($existingImage && !$image)
                                <img src="{{ Storage::url($existingImage) }}" class="mt-4 w-full rounded-lg object-cover">
                            @endif
                            
                            @error('image') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Related Services</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto border rounded-lg p-3 dark:bg-zinc-800 dark:border-zinc-700">
                            @forelse($allServices as $service)
                                <label class="flex items-center gap-2 dark:text-zinc-100">
                                    <input
                                        type="checkbox"
                                        wire:model="selectedServices"
                                        value="{{ $service->id }}"
                                        class="dark:accent-zinc-700"
                                    />
                                    <span class="text-sm">{{ $service->name }}</span>
                                </label>
                            @empty
                                <span class="text-sm text-zinc-500">No services created yet.</span>
                            @endforelse
                        </div>
                        @error('selectedServices') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Attachment File</label>
                        <input
                            type="file"
                            wire:model="attachment"
                            class="w-full text-sm dark:text-zinc-100 file:mr-4 file:rounded-lg file:border-0 file:bg-zinc-900 file:px-4 file:py-2 file:text-white file:dark:bg-white file:dark:text-zinc-900"
                        >
                        <div wire:loading wire:target="attachment" class="mt-2 text-sm dark:text-zinc-100">Uploading...</div>
                        
                        @if ($attachment)
                            @if($attachment->isPreviewable())
                                <img src="{{ $attachment->temporaryUrl() }}" class="mt-2 h-20 w-auto rounded-lg object-cover">
                            @else
                                <div class="mt-2 flex items-center gap-2 p-3 rounded-lg border dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800">
                                    <span class="text-sm dark:text-zinc-200">New File: {{ $attachment->getClientOriginalName() }}</span>
                                    <span class="text-xs text-zinc-500 uppercase">({{ $attachment->extension() }})</span>
                                </div>
                            @endif
                        @elseif ($existingAttachment)
                            <p class="mt-4 text-sm font-medium dark:text-zinc-100">
                                Current File: <a href="{{ Storage::url($existingAttachment) }}" class="text-blue-400" target="_blank">Download</a>
                            </p>
                        @endif

                        @error('attachment') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 border-t dark:border-zinc-700 pt-5">
                    <a
                        href="{{ route('admin.products.index') }}"
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