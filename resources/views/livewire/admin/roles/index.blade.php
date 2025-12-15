<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-black">Roles</h1>
        @can('roles.create')
        <button
            wire:click="create"
            class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition">
            New Role
        </button>
        @endcan
    </div>

    <div class="flex items-center gap-3">
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search roles..."
            class="w-full md:w-80 rounded-lg border border-[#D2D2D2] px-3 py-2 placeholder-[#D2D2D2] focus:outline-none"
        />
    </div>

    <div class="overflow-x-auto rounded-xl border border-[#D2D2D2]">
        <table class="min-w-full text-sm">
            <thead style="color: #000;" class="text-left">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Permissions</th>
                    <th class="px-4 py-3 w-40">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                    <tr class="border-t border-[#D2D2D2]">
                        <td class="px-4 py-3 font-medium text-black">{{ $role->name }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                @foreach($role->permissions as $p)
                                    <span class="px-2 py-0.5 rounded border border-[#D2D2D2] text-xs text-black">{{ $p->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @can('roles.edit')
                                <button
                                    wire:click="edit({{ $role->id }})"
                                    class="px-2 py-1 rounded border border-[#0079C2] text-[#0079C2] bg-transparent flex items-center hover:cursor-pointer transition"
                                    title="Edit"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" style="color:#000000" viewBox="0 0 1200 1200">
                                        <path fill="currentColor" d="M0 0v1200h1200V424.292l-196.875 196.875v381.958h-806.25v-806.25h381.958L775.708 0zm1050 0l-76.831 76.831l150 150L1200 150zM936.914 113.086L497.168 552.832l150 150l439.746-439.746zM441.943 622.339c-2.225.034-4.493.195-6.738.366v142.09h142.09c0-38.708-18.492-78.039-47.314-105.542c-23.842-22.751-54.675-37.428-88.038-36.914"></path>
                                    </svg>
                                </button>
                                @endcan
                                @can('roles.delete')
                                <button
                                    wire:click="delete({{ $role->id }})"
                                    onclick="return confirm('Delete this role?')"
                                    class="px-2 py-1 rounded border border-red-600 text-red-600 bg-transparent flex items-center hover:cursor-pointer transition"
                                    title="Delete"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" style="color:#000000" viewBox="0 0 12 12">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round" d="M2 2.5h8" stroke-width="1"></path>
                                        <path fill="currentColor" d="M2 4v7c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4zm3 5.5c0 .28-.22.5-.5.5S4 9.78 4 9.5V6c0-.28.22-.5.5-.5s.5.22.5.5zm3 0c0 .28-.22.5-.5.5S7 9.78 7 9.5V6c0-.28.22-.5.5-.5s.5.22.5.5zM8 3H4V1c0-.55.45-1 1-1h2c.55 0 1 .45 1 1z"></path>
                                    </svg>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center" style="color: #000;">No roles found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $roles->links() }}</div>

    {{-- Modal --}}
    @if($showForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="w-full max-w-2xl rounded-2xl bg-white p-6">
            <h2 class="text-xl font-semibold mb-4 text-black">{{ $editingId ? 'Edit Role' : 'New Role' }}</h2>
            <form wire:submit.prevent="save" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">Role name</label>
                    <input
                        type="text"
                        wire:model="name"
                        class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2] focus:outline-none"
                        placeholder="Role name"
                    >
                    @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">Permissions</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 max-h-64 overflow-y-auto border border-[#D2D2D2] rounded-lg p-3">
                        @foreach($permissions as $perm)
                            <label class="flex items-center gap-2 text-black">
                                <input
                                    type="checkbox"
                                    wire:model="selectedPermissions"
                                    value="{{ $perm->id }}"
                                    class=""
                                />
                                <span class="text-sm">{{ $perm->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('selectedPermissions.*') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center justify-end gap-2">
                    <button
                        type="button"
                        wire:click="$set('showForm', false)"
                        class="px-4 py-2 rounded-lg border border-[#0079C2] text-[#0079C2] bg-transparent transition"
                    >Cancel</button>
                    <button
                        type="submit"
                        class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition"
                    >Save</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
