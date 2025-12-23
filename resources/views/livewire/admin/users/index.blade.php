<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold" style="color:#000">Users</h1>
        @can('users.create')
            <button wire:click="create"
                class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition">New
                User</button>
        @endcan
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <!-- Card Header: Search -->
        <div class="px-4 py-4 border-b bg-gray-50 rounded-t-xl">
            <div class="max-w-xl w-full">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="7" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-3.5-3.5" />
                        </svg>
                    </span>
                    <input type="text" wire:model.live="search"
                        class="block w-full md:w-96 pl-10 pr-3 py-2 rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        placeholder="Search name or email..." />
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left uppercase text-xs tracking-wider text-gray-500">Name</th>
                        <th class="px-4 py-3 text-left uppercase text-xs tracking-wider text-gray-500">Email</th>
                        <th class="px-4 py-3 text-left uppercase text-xs tracking-wider text-gray-500">Roles</th>
                        <th class="px-4 py-3 text-left uppercase text-xs tracking-wider text-gray-500">Direct Perms</th>
                        <th class="px-4 py-3 text-right uppercase text-xs tracking-wider text-gray-500 w-44">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $u)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $u->name }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $u->email }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($u->roles as $r)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                            {{ $r->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($u->permissions as $p)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            {{ $p->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                <div class="flex justify-end gap-2">
                                    @can('users.edit')
                                        <button wire:click="edit({{ $u->id }})"
                                            class="inline-flex items-center justify-center p-2 rounded hover:bg-blue-50 text-blue-600 hover:text-blue-800 transition"
                                            title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M15.232 5.232l3.536 3.536M9 13l6-6 3 3-6 6zm6.364-6.364a2.121 2.121 0 0 1 3 3L7 21H3v-4L15.364 6.636z" />
                                            </svg>
                                        </button>
                                    @endcan
                                    @can('users.delete')
                                        <button wire:click="delete({{ $u->id }})" onclick="return confirm('Delete this user?')"
                                            class="inline-flex items-center justify-center p-2 rounded hover:bg-red-50 text-red-600 hover:text-red-800 transition"
                                            title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12zM19 7V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v2m5 4v6m4-6v6" />
                                            </svg>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor"
                                        viewBox="0 0 48 48">
                                        <rect width="36" height="24" x="6" y="12" rx="6" fill="none" stroke="currentColor"
                                            stroke-width="2" />
                                        <path d="M18 21a6 6 0 1 1 12 0M6 36c2-4 8-7 18-7s16 3 18 7" stroke="currentColor"
                                            stroke-width="2" fill="none" />
                                    </svg>
                                    <div class="text-lg font-semibold text-gray-500">No users found</div>
                                    <div class="text-sm text-gray-400 text-center">We couldn't find any users matching your
                                        search.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination: Card Footer -->
        <div class="px-4 py-3 border-t bg-gray-50 rounded-b-xl">
            {{ $users->links() }}
        </div>
    </div>

    {{-- Modal --}}
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center" style="background: rgba(0,0,0,0.4)">
            <div class="w-full max-w-3xl rounded-2xl p-6 bg-white">
                <h2 class="text-xl font-semibold mb-4" style="color: #000">{{ $editingId ? 'Edit User' : 'New User' }}</h2>

                <form wire:submit.prevent="save" class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color:#AEAEAE">Name</label>
                            <input type="text" wire:model="name" class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2"
                                style="color:#000; border-color:#D2D2D2;" placeholder="Name">
                            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color:#AEAEAE">Email</label>
                            <input type="email" wire:model="email"
                                class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2"
                                style="color:#000; border-color:#D2D2D2;" placeholder="Email">
                            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color:#AEAEAE">Password
                                {{ $editingId ? '(leave blank to keep)' : '' }}</label>
                            <input type="password" wire:model="password"
                                class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2"
                                style="color:#000; border-color:#D2D2D2;" placeholder="Password">
                            @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color:#AEAEAE">Pricing Formula</label>
                            <select wire:model="pricing_formula_id"
                                class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2"
                                style="color:#000; border-color:#D2D2D2;">
                                <option value="">None</option>
                                @foreach($pricingFormulas as $formula)
                                    <option value="{{ $formula->id }}">{{ $formula->label }} ({{ $formula->type->label() }}
                                        {{ $formula->value }})</option>
                                @endforeach
                            </select>
                            @error('pricing_formula_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color:#AEAEAE">Roles</label>
                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-56 overflow-y-auto border border-[#D2D2D2] rounded-lg p-3 bg-white">
                                @foreach($roles as $role)
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" wire:model="roleIds" value="{{ $role->id }}"
                                            class="border-[#D2D2D2]" />
                                        <span class="text-sm" style="color:#000;">{{ $role->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" style="color:#AEAEAE">Direct Permissions
                                (optional)</label>
                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-56 overflow-y-auto border border-[#D2D2D2] rounded-lg p-3 bg-white">
                                @foreach($permissions as $perm)
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" wire:model="permissionIds" value="{{ $perm->id }}"
                                            class="border-[#D2D2D2]" />
                                        <span class="text-sm" style="color:#000;">{{ $perm->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <button type="button" wire:click="$set('showForm', false)"
                            class="px-4 py-2 rounded-lg border border-[#0079C2] text-[#0079C2] bg-white hover:cursor-pointer transition">Cancel</button>
                        <button type="submit"
                            class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>