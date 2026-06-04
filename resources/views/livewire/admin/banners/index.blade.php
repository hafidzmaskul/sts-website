<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-black">Banner Management</h1>
        @can('banner.create')
            <button wire:click="create" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            New Banner
        </button>
        @endcan
    </div>

    @if($showForm)
        <div class="p-6 rounded-2xl shadow border border-[#e5e7eb]">
            <h2 class="text-xl font-semibold mb-4 text-black">{{ $editingId ? 'Edit Banner' : 'Create Banner' }}</h2>

            <form wire:submit.prevent="save" class="space-y-5">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">Name</label>
                        <input type="text" wire:model="name"
                            class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black"
                            placeholder="Name"
                            style="border-color:#D2D2D2; color:#000;"/>
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- CTA URL -->
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">CTA URL (Optional)</label>
                        <input type="url" wire:model="cta_url"
                            class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black"
                            style="border-color:#D2D2D2; color:#000;"
                            placeholder="https://example.com">
                        @error('cta_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700">Banner Image</label>
                        <input type="file" wire:model="file"
                            class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                        <div wire:loading wire:target="file" class="text-sm text-gray-700 mt-1">Uploading...</div>

                        @if ($file)
                            <div class="mt-2">
                                <p class="text-sm mb-1 text-gray-700">Preview:</p>
                                <img src="{{ $file->temporaryUrl() }}"
                                    class="h-32 w-auto object-cover rounded border"/>
                            </div>
                        @elseif ($editingId && $editingId)
                            @php
                                $currentBanner = \App\Models\Banner::find($editingId);
                            @endphp
                            @if($currentBanner && $currentBanner->file_path)
                                <div class="mt-2">
                                    <p class="text-sm mb-1 text-gray-700">Current Image:</p>
                                    <img src="{{ Storage::url($currentBanner->file_path) }}"
                                        class="h-32 w-auto object-cover rounded border">
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" wire:click="cancel"
                        class="w-full md:w-auto border border-[#0079C2] text-[#0079C2] px-4 py-2 rounded-lg hover:cursor-pointer transition bg-white">Cancel</button>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Save
        </button>
                </div>
            </form>
        </div>
    @else
        <!-- Card Container -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            <!-- Card Header with Search -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex flex-col sm:flex-row items-center gap-3">
                <div class="w-full sm:max-w-xs relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <!-- Search Icon -->
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2" fill="none"/>
                            <path stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M21 21l-2-2" />
                        </svg>
                    </span>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search banners..."
                        class="block w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 bg-white text-black placeholder-gray-400 text-sm transition"
                        autocomplete="off"
                    >
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-700">Image</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-700">Name</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-700">CTA URL</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-700">Created By</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-700 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($banners as $banner)
                            <tr class="hover:bg-gray-50 transition">
                                <!-- Image cell -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($banner->file_path)
                                        <span class="inline-flex items-center rounded-md overflow-hidden">
                                            <img src="{{ Storage::url($banner->file_path) }}" class="h-12 w-16 object-cover rounded-md border border-gray-200" alt="Banner Image">
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full px-3 py-1 bg-gray-100 text-xs text-gray-400">No Image</span>
                                    @endif
                                </td>
                                <!-- Name cell -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-black">{{ $banner->name }}</div>
                                </td>
                                <!-- CTA URL cell -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($banner->cta_url)
                                        <a href="{{ $banner->cta_url }}" target="_blank" class="text-xs text-blue-700 hover:underline truncate max-w-[160px] block transition">
                                            {{ $banner->cta_url }}
                                        </a>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-100 text-gray-400 text-xs px-2 py-0.5">-</span>
                                    @endif
                                </td>
                                <!-- Created By cell -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-black">
                                        {{ $banner->creator->name ?? '-' }}
                                    </div>
                                </td>
                                <!-- Actions cell -->
                                <td class="px-6 py-4 whitespace-nowrap text-right">
<div class="flex items-center justify-end gap-2">
<div class="flex justify-end gap-2">
                                        @can('banner.edit')
                                            <button wire:click="edit({{ $banner->id }})" class="text-black hover:text-blue-600 transition-colors inline-flex" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                        @endcan
                                        @can('banner.delete')
                                            <button wire:confirm="Are you sure you want to delete this banner?" wire:click="delete({{ $banner->id }})" class="text-black hover:text-red-600 transition-colors inline-flex" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                        @endcan
                                    </div>
</div>
</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12">
                                    <div class="flex flex-col items-center justify-center text-center gap-4">
                                        <span class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 text-gray-300">
                                            <!-- Banner icon SVG -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <rect width="18" height="14" x="3" y="5" rx="2" fill="currentColor" class="text-gray-200"/>
                                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M3 19l5.299-7.582a1.5 1.5 0 0 1 2.39-.034l2.573 3.515a1.5 1.5 0 0 0 2.39.03L21 8"/>
                                            </svg>
                                        </span>
                                        <div class="text-lg font-semibold text-gray-500">No banners found</div>
                                        <div class="text-sm text-gray-400">Try adjusting your search or add new banners.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-between items-center">
                <div>
                    {{-- You may display count/page info here if desired --}}
                </div>
                <div>
                    {{ $banners->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
