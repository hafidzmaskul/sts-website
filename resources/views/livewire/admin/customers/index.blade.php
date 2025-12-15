<div class="p-6 space-y-6 dark:bg-zinc-900">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Customers</h1>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow dark:bg-zinc-900 dark:border dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-zinc-400">Total Customers</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-xl dark:bg-blue-900/20">
                    <flux:icon.users class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow dark:bg-zinc-900 dark:border dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-zinc-400">Active</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['active'] }}</p>
                </div>
                <div class="p-3 bg-green-50 rounded-xl dark:bg-green-900/20">
                    <flux:icon.check-circle class="w-6 h-6 text-green-600 dark:text-green-400" />
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow dark:bg-zinc-900 dark:border dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-zinc-400">Suspended</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['suspended'] }}</p>
                </div>
                <div class="p-3 bg-red-50 rounded-xl dark:bg-red-900/20">
                    <flux:icon.x-circle class="w-6 h-6 text-red-600 dark:text-red-400" />
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <!-- Filters -->
    <div class="flex items-center gap-3">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search customers..."
            class="w-full md:w-80 rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">

        <select wire:model.live="status"
            class="rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="suspended">Suspended</option>
        </select>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-xl border dark:border-zinc-700 dark:bg-zinc-800">
        <table class="min-w-full text-sm">
            <thead class="bg-zinc-50 text-left dark:bg-zinc-900 dark:text-zinc-200">
                <tr>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider">Phone</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse($customers as $user)
                    <tr class="dark:text-zinc-100">
                        <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400">
                            {{ $user->customer?->phone ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($user->customer?->status === 'active') ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                {{ ucfirst($user->customer?->status ?? 'Active') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium space-x-2">
                            @can('customers.view')
                                <a href="{{ route('admin.customers.show', $user) }}"
                                    class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">View</a>
                            @endcan
                            @can('customers.edit')
                                <a href="{{ route('admin.customers.edit', $user) }}"
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-zinc-400">No customers
                            found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $customers->links() }}
    </div>
</div>