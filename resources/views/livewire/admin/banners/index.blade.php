<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold" style="color:#000;">Banner Management</h1>
        @can('banner.create')
            <button wire:click="create"
                class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition">
                + New Banner
            </button>
        @endcan
    </div>

    @if($showForm)
        <div class="p-6 rounded-2xl shadow border border-[#e5e7eb]">
            <h2 class="text-xl font-semibold mb-4" style="color:#000;">{{ $editingId ? 'Edit Banner' : 'Create Banner' }}</h2>

            <form wire:submit.prevent="save" class="space-y-5">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">Name</label>
                        <input type="text" wire:model="name"
                            class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black"
                            placeholder="Name"
                            style="border-color:#D2D2D2; color:#000;"/>
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- CTA URL -->
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">CTA URL (Optional)</label>
                        <input type="url" wire:model="cta_url"
                            class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black"
                            style="border-color:#D2D2D2; color:#000;"
                            placeholder="https://example.com">
                        @error('cta_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">Banner Image</label>
                        <input type="file" wire:model="file"
                            class="block w-full text-sm text-[#AEAEAE] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            style="color:#AEAEAE;">
                        @error('file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                        <div wire:loading wire:target="file" class="text-sm text-[#AEAEAE] mt-1">Uploading...</div>

                        @if ($file)
                            <div class="mt-2">
                                <p class="text-sm mb-1" style="color:#AEAEAE;">Preview:</p>
                                <img src="{{ $file->temporaryUrl() }}"
                                    class="h-32 w-auto object-cover rounded border"/>
                            </div>
                        @elseif ($editingId && $editingId)
                            @php
                                $currentBanner = \App\Models\Banner::find($editingId);
                            @endphp
                            @if($currentBanner && $currentBanner->file_path)
                                <div class="mt-2">
                                    <p class="text-sm mb-1" style="color:#AEAEAE;">Current Image:</p>
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
                    <button type="submit"
                        class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition">Save</button>
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
                        class="block w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 bg-white text-gray-900 placeholder-gray-400 text-sm transition"
                        autocomplete="off"
                    >
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Image</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Name</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">CTA URL</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Created By</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">Actions</th>
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
                                    <div class="text-sm font-medium text-gray-900">{{ $banner->name }}</div>
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
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $banner->creator->name ?? '-' }}
                                    </div>
                                </td>
                                <!-- Actions cell -->
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-2">
                                        @can('banner.edit')
                                            <button wire:click="edit({{ $banner->id }})"
                                                class="inline-flex items-center justify-center p-2 rounded-full bg-white text-blue-600 border border-blue-100 hover:text-blue-800 hover:bg-blue-50 transition"
                                                title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M4 21v-3.9c0-.28.11-.53.29-.71l10.1-10.1 4.6 4.6-10.1 10.1a1 1 0 0 1-.71.29H4zm13.71-12.29a1 1 0 0 0 0-1.42l-2-2a1 1 0 0 0-1.42 0l-1.34 1.34 4.6 4.6z"/>
                                                </svg>
                                            </button>
                                        @endcan
                                        @can('banner.delete')
                                            <button
                                                wire:confirm="Are you sure you want to delete this banner?"
                                                wire:click="delete({{ $banner->id }})"
                                                class="inline-flex items-center justify-center p-2 rounded-full bg-white text-red-600 border border-red-100 hover:text-red-800 hover:bg-red-50 transition"
                                                title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                                                    <path fill="currentColor" d="M5.77 3.5v-.25A1.25 1.25 0 0 1 7.02 2h1.96A1.25 1.25 0 0 1 10.23 3.25v.25h3a.75.75 0 0 1 0 1.5h-.38l-.5 8.03A2 2 0 0 1 10.36 15H5.64a2 2 0 0 1-1.99-1.97l-.5-8.03H2.75a.75.75 0 0 1 0-1.5zm1.73-.25v.25h1V3.25a.25.25 0 0 0-.25-.25H7.02a.25.25 0 0 0-.25.25zm4.12 1.5H4.38l.5 8a.5.5 0 0 0 .5.5h4.72a.5.5 0 0 0 .5-.5z"/>
                                                </svg>
                                            </button>
                                        @endcan
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
