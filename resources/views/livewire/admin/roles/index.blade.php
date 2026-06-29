<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold" style="color: #000;">Roles</h1>
        @can('roles.create')
            <button wire:click="create" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            New Role
        </button>
        @endcan
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <!-- Header: Search -->
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <div class="relative max-w-full md:max-w-xs">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <!-- Search Icon SVG -->
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" wire:model.live="search" placeholder="Search roles..."
                    class="block w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 bg-white placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" style="color: #000;" />
            </div>
        </div>
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th scope="col"
                            class="px-6 py-3 text-xs font-semibold uppercase tracking-wider whitespace-nowrap"
                            style="color: #000;">
                            Name
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-semibold uppercase tracking-wider whitespace-nowrap"
                            style="color: #000;">
                            Permissions
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-semibold uppercase tracking-wider whitespace-nowrap text-right w-40"
                            style="color: #000;">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($roles as $role)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium" style="color: #000;">{{ $role->name }}</div>
                                {{-- Optionally subtext here --}}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($role->permissions as $p)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 text-xs font-semibold"
                                            style="color: #000;">
                                            {{ $p->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
<div class="flex items-center justify-end gap-2">
<div class="flex justify-end gap-2">
                                    @can('roles.edit')
                                        @if(!in_array($role->name, ['admin', 'guest', 'trade account', 'credit facilities account', 'child']))
                                            <button wire:click="edit({{ $role->id }})" class="text-black hover:text-blue-600 transition-colors inline-flex" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                        @endif
                                    @endcan
                                    @can('roles.delete')
                                        @if(!in_array($role->name, ['admin', 'guest', 'trade account', 'credit facilities account', 'child']))
                                            <button wire:confirm="Are you sure you want to delete this?" wire:click="delete({{ $role->id }})" class="text-black hover:text-red-600 transition-colors inline-flex" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                        @endif
                                    @endcan
                                </div>
</div>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor"
                                        stroke-width="1.5" viewBox="0 0 64 64">
                                        <rect x="12" y="20" width="40" height="28" rx="4" fill="currentColor" />
                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                            d="M24 28h16M24 36h10" />
                                    </svg>
                                    <div class="text-lg font-semibold mb-1" style="color: #000;">No roles found</div>
                                    <div class="text-sm" style="color: #757575;">Try adjusting your search or create a new role.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination/ Footer -->
        <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 overflow-x-auto">
            {{ $roles->links() }}
        </div>
    </div>

    {{-- Modal --}}
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="w-full max-w-2xl rounded-2xl bg-white p-6">
                <h2 class="text-xl font-semibold mb-4" style="color: #000;">{{ $editingId ? 'Edit Role' : 'New Role' }}</h2>
                <form wire:submit.prevent="save" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">Role name</label>
                        <input type="text" wire:model="name"
                            class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 placeholder-[#D2D2D2] focus:outline-none"
                            style="color: #000;"
                            placeholder="Role name">
                        @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">Permissions</label>
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 max-h-64 overflow-y-auto border border-[#D2D2D2] rounded-lg p-3">
                            @foreach($permissions as $perm)
                                <label class="flex items-center gap-2" style="color: #000;">
                                    <input type="checkbox" wire:model="selectedPermissions" value="{{ $perm->id }}" class="" />
                                    <span class="text-sm">{{ $perm->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('selectedPermissions.*') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-center justify-end gap-2">
                        <button type="button" wire:click="$set('showForm', false)"
                            class="px-4 py-2 rounded-lg border border-[#0079C2] text-[#0079C2] bg-transparent transition">Cancel</button>
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Save
        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
