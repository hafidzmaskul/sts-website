<div class="p-6 space-y-6 dark:bg-zinc-900">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Banner Management</h1>
        @can('banner.create')
            <button wire:click="create"
                class="px-4 py-2 bg-zinc-900 text-white rounded-lg hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 transition-colors">
                + New Banner
            </button>
        @endcan
    </div>

    @if($showForm)
        <div class="bg-white p-6 rounded-2xl shadow dark:bg-zinc-900 dark:border dark:border-zinc-700">
            <h2 class="text-xl font-semibold mb-4 dark:text-white">{{ $editingId ? 'Edit Banner' : 'Create Banner' }}</h2>

            <form wire:submit.prevent="save" class="space-y-5">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Name</label>
                        <input type="text" wire:model="name"
                            class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- CTA URL -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">CTA URL
                            (Optional)</label>
                        <input type="url" wire:model="cta_url"
                            class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                            placeholder="https://example.com">
                        @error('cta_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-100 mb-1">Banner Image</label>
                        <input type="file" wire:model="file"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:text-zinc-300 dark:file:bg-zinc-700 dark:file:text-zinc-100">
                        @error('file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                        <div wire:loading wire:target="file" class="text-sm text-gray-500 mt-1">Uploading...</div>

                        @if ($file)
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-1 dark:text-zinc-400">Preview:</p>
                                <img src="{{ $file->temporaryUrl() }}"
                                    class="h-32 w-auto object-cover rounded border dark:border-zinc-600">
                            </div>
                        @elseif ($editingId && $editingId)
                            @php
                                $currentBanner = \App\Models\Banner::find($editingId);
                            @endphp
                            @if($currentBanner && $currentBanner->file_path)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-1 dark:text-zinc-400">Current Image:</p>
                                    <img src="{{ Storage::url($currentBanner->file_path) }}"
                                        class="h-32 w-auto object-cover rounded border dark:border-zinc-600">
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" wire:click="cancel"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-zinc-900 text-white rounded-lg hover:bg-zinc-800 dark:bg-white dark:text-zinc-900">Save</button>
                </div>
            </form>
        </div>
    @else
        <!-- Search -->
        <div class="flex items-center gap-3">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search banners..."
                class="w-full md:w-80 rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border dark:border-zinc-700 dark:bg-zinc-800">
            <table class="min-w-full text-sm">
                <thead class="bg-zinc-50 text-left dark:bg-zinc-900 dark:text-zinc-200">
                    <tr>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider">CTA URL</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider">Created By</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse($banners as $banner)
                        <tr class="dark:text-zinc-100">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($banner->file_path)
                                    <img src="{{ Storage::url($banner->file_path) }}" class="h-12 w-auto object-cover rounded">
                                @else
                                    <span class="text-gray-400">No Image</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $banner->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400">
                                @if($banner->cta_url)
                                    <a href="{{ $banner->cta_url }}" target="_blank"
                                        class="text-blue-600 hover:underline truncate max-w-xs block dark:text-blue-400">{{ $banner->cta_url }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400">
                                {{ $banner->creator->name ?? 'Unknown' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium space-x-2">
                                @can('banner.edit')
                                    <button wire:click="edit({{ $banner->id }})"
                                        class="px-3 py-1.5 rounded border dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 hover:bg-gray-50 dark:hover:bg-zinc-700">Edit</button>
                                @endcan
                                @can('banner.delete')
                                    <button wire:confirm="Are you sure you want to delete this banner?"
                                        wire:click="delete({{ $banner->id }})"
                                        class="px-3 py-1.5 rounded border text-red-600 dark:border-zinc-600 dark:bg-zinc-800 dark:text-red-400 hover:bg-red-50 dark:hover:bg-zinc-700">Delete</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-zinc-400">No banners found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $banners->links() }}
        </div>
    @endif
</div>