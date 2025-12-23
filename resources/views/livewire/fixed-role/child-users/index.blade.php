<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-black">Manage Users</h1>
        <button wire:click="create"
            class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition">
            Add New User
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th scope="col"
                            class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Name
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Email
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                            Role
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap text-right w-40">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-700">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50 text-xs font-medium text-blue-700">
                                    {{ ucfirst($user->getRoleNames()->first()) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex justify-end gap-2">
                                    <button wire:click="edit({{ $user->id }})"
                                        class="inline-flex items-center p-2 rounded-full text-blue-600 hover:bg-blue-50 transition"
                                        title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.232 5.232l3.536 3.536M9 13l6.536-6.536a2 2 0 112.828 2.828L11.828 15.828A2 2 0 019 17v0a2 2 0 01-2-2v0a2 2 0 012-2zm0 0L5 19" />
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $user->id }})"
                                        onclick="return confirm('Are you sure you want to delete this user?')"
                                        class="inline-flex items-center p-2 rounded-full text-red-600 hover:bg-red-50 transition"
                                        title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor"
                                        stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <div class="text-lg font-semibold text-gray-700 mb-1">No users found</div>
                                    <div class="text-sm text-gray-400">Get started by creating a new child user.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="px-6 py-3 border-t border-gray-100 bg-gray-50">
            {{ $users->links() }}
        </div>
    </div>

    {{-- Modal --}}
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="w-full max-w-lg rounded-2xl bg-white p-6">
                <h2 class="text-xl font-semibold mb-4 text-black">{{ $editingId ? 'Edit User' : 'New User' }}</h2>
                <form wire:submit.prevent="save" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">Name</label>
                        <input type="text" wire:model="name"
                            class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2] focus:outline-none">
                        @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">Email</label>
                        <input type="email" wire:model="email"
                            class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2] focus:outline-none">
                        @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">Password</label>
                        <input type="password" wire:model="password"
                            class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2] focus:outline-none"
                            placeholder="{{ $editingId ? 'Leave blank to keep current' : '' }}">
                        @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#AEAEAE;">Confirm Password</label>
                        <input type="password" wire:model="password_confirmation"
                            class="w-full rounded-lg border border-[#D2D2D2] px-3 py-2 text-black placeholder-[#D2D2D2] focus:outline-none">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" wire:click="$set('showForm', false)"
                            class="px-4 py-2 rounded-lg border border-[#0079C2] text-[#0079C2] bg-transparent transition">Cancel</button>
                        <button type="submit"
                            class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>