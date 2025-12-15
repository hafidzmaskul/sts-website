<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold" style="color:#000">Users</h1>
        @can('users.create')
        <button wire:click="create" class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition">New User</button>
        @endcan
    </div>

    <div class="flex items-center gap-3">
        <input type="text" wire:model.live="search" placeholder="Search name or email..."
               class="w-full md:w-96 rounded-lg border border-[#D2D2D2] px-3 py-2" style="color:#000; border-color:#D2D2D2;" placeholder="Search name or email..." />
    </div>

    <div class="overflow-x-auto rounded-xl border border-[#D2D2D2]">
        <table class="min-w-full text-sm bg-white">
            <thead style="background: #fff;">
                <tr>
                    <th class="px-4 py-3" style="color:#AEAEAE">Name</th>
                    <th class="px-4 py-3" style="color:#AEAEAE">Email</th>
                    <th class="px-4 py-3" style="color:#AEAEAE">Roles</th>
                    <th class="px-4 py-3" style="color:#AEAEAE">Direct Perms</th>
                    <th class="px-4 py-3 w-44" style="color:#AEAEAE">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr class="border-t" style="border-color: #D2D2D2;">
                        <td class="px-4 py-3 font-medium" style="color:#000">{{ $u->name }}</td>
                        <td class="px-4 py-3" style="color:#000">{{ $u->email }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                @foreach($u->roles as $r)
                                    <span class="px-2 py-0.5 rounded border border-[#D2D2D2] text-xs" style="color:#000;">{{ $r->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                @foreach($u->permissions as $p)
                                    <span class="px-2 py-0.5 rounded border border-[#D2D2D2] text-xs" style="color:#000;">{{ $p->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @can('users.edit')
                                <button wire:click="edit({{ $u->id }})" class="px-2 py-2 rounded border border-[#0079C2] text-[#0079C2] bg-white hover:cursor-pointer transition flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 1200 1200" style="color:#000;">
                                        <path fill="currentColor" d="M0 0v1200h1200V424.292l-196.875 196.875v381.958h-806.25v-806.25h381.958L775.708 0zm1050 0l-76.831 76.831l150 150L1200 150zM936.914 113.086L497.168 552.832l150 150l439.746-439.746zM441.943 622.339c-2.225.034-4.493.195-6.738.366v142.09h142.09c0-38.708-18.492-78.039-47.314-105.542c-23.842-22.751-54.675-37.428-88.038-36.914"></path>
                                    </svg>
                                </button>
                                @endcan
                                @can('users.delete')
                                <button wire:click="delete({{ $u->id }})"
                                        onclick="return confirm('Delete this user?')"
                                        class="px-2 py-2 rounded border border-[#000000] text-[#000000] bg-white hover:cursor-pointer transition flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 12 12" style="color:#000;">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round" d="M2 2.5h8" stroke-width="1"></path>
                                        <path fill="currentColor" d="M2 4v7c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4zm3 5.5c0 .28-.22.5-.5.5S4 9.78 4 9.5V6c0-.28.22-.5.5-.5s.5.22.5.5zm3 0c0 .28-.22.5-.5.5S7 9.78 7 9.5V6c0-.28.22-.5.5-.5s.5.22.5.5zM8 3H4V1c0-.55.45-1 1-1h2c.55 0 1 .45 1 1z"></path>
                                    </svg>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center" style="color:#AEAEAE;">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $users->links() }}</div>

    {{-- Modal --}}
    @if($showForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center" style="background: rgba(0,0,0,0.4)">
        <div class="w-full max-w-3xl rounded-2xl p-6 bg-white">
            <h2 class="text-xl font-semibold mb-4" style="color: #000">{{ $editingId ? 'Edit User' : 'New User' }}</h2>

            <form wire:submit.prevent="save" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#AEAEAE">Name</label>
                        <input type="text" wire:model="name" class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2" style="color:#000; border-color:#D2D2D2;" placeholder="Name">
                        @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#AEAEAE">Email</label>
                        <input type="email" wire:model="email" class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2" style="color:#000; border-color:#D2D2D2;" placeholder="Email">
                        @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#AEAEAE">Password {{ $editingId ? '(leave blank to keep)' : '' }}</label>
                        <input type="password" wire:model="password" class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2" style="color:#000; border-color:#D2D2D2;" placeholder="Password">
                        @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color:#AEAEAE">Roles</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-56 overflow-y-auto border border-[#D2D2D2] rounded-lg p-3 bg-white">
                            @foreach($roles as $role)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" wire:model="roleIds" value="{{ $role->id }}" class="border-[#D2D2D2]" />
                                    <span class="text-sm" style="color:#000;">{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color:#AEAEAE">Direct Permissions (optional)</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-56 overflow-y-auto border border-[#D2D2D2] rounded-lg p-3 bg-white">
                            @foreach($permissions as $perm)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" wire:model="permissionIds" value="{{ $perm->id }}" class="border-[#D2D2D2]" />
                                    <span class="text-sm" style="color:#000;">{{ $perm->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2 rounded-lg border border-[#0079C2] text-[#0079C2] bg-white hover:cursor-pointer transition">Cancel</button>
                    <button type="submit" class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition">Save</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
