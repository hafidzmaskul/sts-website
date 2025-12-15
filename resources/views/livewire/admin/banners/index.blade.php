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
        <!-- Search -->
        <div class="flex items-center gap-3">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search banners..."
                class="w-full md:w-80 rounded-lg border border-[#D2D2D2] px-3 py-2 text-black"
                style="color:#000; border-color:#D2D2D2;::placeholder{color:#D2D2D2;}"
                placeholder="Search banners...">
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border border-[#e5e7eb] bg-white">
            <table class="min-w-full text-sm">
                <thead style="background-color:#fff;" class="text-left">
                    <tr>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color:#AEAEAE;">Image</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color:#AEAEAE;">Name</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color:#AEAEAE;">CTA URL</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color:#AEAEAE;">Created By</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color:#AEAEAE;">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($banners as $banner)
                        <tr style="color:#000;" class="text-black">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($banner->file_path)
                                    <img src="{{ Storage::url($banner->file_path) }}" class="h-12 w-auto object-cover rounded">
                                @else
                                    <span class="" style="color:#AEAEAE;">No Image</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $banner->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap" style="color:#000;">
                                @if($banner->cta_url)
                                    <a href="{{ $banner->cta_url }}" target="_blank"
                                        class="truncate max-w-xs block" style="color:#0079C2;">{{ $banner->cta_url }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap" style="color:#000;">
                                {{ $banner->creator->name ?? 'Unknown' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium space-x-2">
                                @can('banner.edit')
                                    <button wire:click="edit({{ $banner->id }})"
                                        class="border border-[#0079C2] text-[#0079C2] rounded flex items-center justify-center p-1 hover:cursor-pointer transition bg-white"
                                        style="display: inline-flex;align-items:center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="color:#000;" viewBox="0 0 1200 1200">
                                            <path fill="currentColor" d="M0 0v1200h1200V424.292l-196.875 196.875v381.958h-806.25v-806.25h381.958L775.708 0zm1050 0l-76.831 76.831l150 150L1200 150zM936.914 113.086L497.168 552.832l150 150l439.746-439.746zM441.943 622.339c-2.225.034-4.493.195-6.738.366v142.09h142.09c0-38.708-18.492-78.039-47.314-105.542c-23.842-22.751-54.675-37.428-88.038-36.914"></path>
                                        </svg>
                                    </button>
                                @endcan
                                @can('banner.delete')
                                    <button wire:confirm="Are you sure you want to delete this banner?"
                                        wire:click="delete({{ $banner->id }})"
                                        class="border border-red-600 text-red-600 rounded flex items-center justify-center p-1 hover:cursor-pointer transition bg-white"
                                        style="display: inline-flex;align-items:center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="color:#000;" viewBox="0 0 12 12">
                                            <path fill="none" stroke="currentColor" stroke-linecap="round" d="M2 2.5h8" stroke-width="1"></path>
                                            <path fill="currentColor" d="M2 4v7c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4zm3 5.5c0 .28-.22.5-.5.5S4 9.78 4 9.5V6c0-.28.22-.5.5-.5s.5.22.5.5zm3 0c0 .28-.22.5-.5.5S7 9.78 7 9.5V6c0-.28.22-.5.5-.5s.5.22.5.5zM8 3H4V1c0-.55.45-1 1-1h2c.55 0 1 .45 1 1z"></path>
                                        </svg>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center" style="color:#AEAEAE;">No banners found.</td>
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
