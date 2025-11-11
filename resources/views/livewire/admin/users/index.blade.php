<div class="p-6 space-y-6 dark:bg-zinc-900">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold dark:text-white">Users</h1>
        @can('users.create')
        <button wire:click="create" class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 transition-colors">New User</button>
        @endcan
    </div>

    <div class="flex items-center gap-3">
        <input type="text" wire:model.live="search" placeholder="Search name or email..."
               class="w-full md:w-96 rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700" />
    </div>

    <div class="overflow-x-auto rounded-xl border dark:border-zinc-700 dark:bg-zinc-800">
        <table class="min-w-full text-sm">
            <thead class="bg-zinc-50 text-left dark:bg-zinc-900 dark:text-zinc-200">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Roles</th>
                    <th class="px-4 py-3">Direct Perms</th>
                    <th class="px-4 py-3 w-44">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr class="border-t dark:border-zinc-700">
                        <td class="px-4 py-3 font-medium dark:text-white">{{ $u->name }}</td>
                        <td class="px-4 py-3 dark:text-zinc-100">{{ $u->email }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                @foreach($u->roles as $r)
                                    <span class="px-2 py-0.5 rounded border text-xs dark:bg-zinc-900 dark:border-zinc-700 dark:text-white">{{ $r->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                @foreach($u->permissions as $p)
                                    <span class="px-2 py-0.5 rounded border text-xs dark:bg-zinc-900 dark:border-zinc-700 dark:text-white">{{ $p->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @can('users.edit')
                                <button wire:click="edit({{ $u->id }})" class="px-3 py-1.5 rounded border dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">Edit</button>
                                @endcan
                                @can('users.delete')
                                <button wire:click="delete({{ $u->id }})"
                                        onclick="return confirm('Delete this user?')"
                                        class="px-3 py-1.5 rounded border text-red-600 dark:text-red-400 dark:border-zinc-600 dark:bg-zinc-800">Delete</button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-zinc-500 dark:text-zinc-400">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $users->links() }}</div>

    {{-- Modal --}}
    @if($showForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="w-full max-w-3xl rounded-2xl bg-white p-6 dark:bg-zinc-900">
            <h2 class="text-xl font-semibold mb-4 dark:text-white">{{ $editingId ? 'Edit User' : 'New User' }}</h2>

            <form wire:submit.prevent="save" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-200">Name</label>
                        <input type="text" wire:model="name" class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                        @error('name') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-200">Email</label>
                        <input type="email" wire:model="email" class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                        @error('email') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-200">Password {{ $editingId ? '(leave blank to keep)' : '' }}</label>
                        <input type="password" wire:model="password" class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                        @error('password') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-zinc-200">Roles</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-56 overflow-y-auto border rounded-lg p-3 dark:bg-zinc-800 dark:border-zinc-700">
                            @foreach($roles as $role)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" wire:model="roleIds" value="{{ $role->id }}" class="dark:bg-zinc-800 dark:border-zinc-700" />
                                    <span class="text-sm dark:text-white">{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-zinc-200">Direct Permissions (optional)</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-56 overflow-y-auto border rounded-lg p-3 dark:bg-zinc-800 dark:border-zinc-700">
                            @foreach($permissions as $perm)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" wire:model="permissionIds" value="{{ $perm->id }}" class="dark:bg-zinc-800 dark:border-zinc-700" />
                                    <span class="text-sm dark:text-white">{{ $perm->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2 rounded-lg border dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 transition-colors">Save</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
