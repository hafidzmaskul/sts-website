<div class="p-6 space-y-6 dark:bg-zinc-900">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold dark:text-white">Roles</h1>
        @can('roles.create')
        <button wire:click="create" class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 transition-colors">New Role</button>
        @endcan
    </div>

    <div class="flex items-center gap-3">
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search roles..."
            class="w-full md:w-80 rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
        />
    </div>

    <div class="overflow-x-auto rounded-xl border dark:border-zinc-700 dark:bg-zinc-800">
        <table class="min-w-full text-sm">
            <thead class="bg-zinc-50 text-left dark:bg-zinc-900 dark:text-zinc-200">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Permissions</th>
                    <th class="px-4 py-3 w-40">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                    <tr class="border-t dark:border-zinc-700">
                        <td class="px-4 py-3 font-medium dark:text-white">{{ $role->name }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                @foreach($role->permissions as $p)
                                    <span class="px-2 py-0.5 rounded border text-xs dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100">{{ $p->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @can('roles.edit')
                                <button
                                    wire:click="edit({{ $role->id }})"
                                    class="px-3 py-1.5 rounded border dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                                >Edit</button>
                                @endcan
                                @can('roles.delete')
                                <button
                                    wire:click="delete({{ $role->id }})"
                                    onclick="return confirm('Delete this role?')"
                                    class="px-3 py-1.5 rounded border text-red-600 dark:border-zinc-600 dark:bg-zinc-800 dark:text-red-400"
                                >Delete</button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-zinc-500 dark:text-zinc-400">No roles found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $roles->links() }}</div>

    {{-- Modal --}}
    @if($showForm)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="w-full max-w-2xl rounded-2xl bg-white p-6 dark:bg-zinc-900">
            <h2 class="text-xl font-semibold mb-4 dark:text-white">{{ $editingId ? 'Edit Role' : 'New Role' }}</h2>

            <form wire:submit.prevent="save" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Role name</label>
                    <input
                        type="text"
                        wire:model="name"
                        class="w-full rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
                    >
                    @error('name') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Permissions</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 max-h-64 overflow-y-auto border rounded-lg p-3 dark:bg-zinc-800 dark:border-zinc-700">
                        @foreach($permissions as $perm)
                            <label class="flex items-center gap-2 dark:text-zinc-100">
                                <input
                                    type="checkbox"
                                    wire:model="selectedPermissions"
                                    value="{{ $perm->id }}"
                                    class="dark:accent-zinc-700"
                                />
                                <span class="text-sm">{{ $perm->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('selectedPermissions.*') <p class="text-sm text-red-600 mt-1 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-2">
                    <button
                        type="button"
                        wire:click="$set('showForm', false)"
                        class="px-4 py-2 rounded-lg border dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                    >Cancel</button>
                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 transition-colors"
                    >Save</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
