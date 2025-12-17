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

    <div
        class="bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-zinc-900 dark:border-zinc-700 overflow-hidden">
        <!-- Search & Filters -->
        <div
            class="p-4 border-b border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900/50 flex flex-col md:flex-row gap-4 justify-between">
            <div class="relative max-w-md w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <flux:icon.magnifying-glass class="w-5 h-5 text-gray-400" />
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search customers..."
                    class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <select wire:model.live="status"
                    class="rounded-lg border border-gray-200 px-3 py-2 bg-white text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="suspended">Suspended</option>
                </select>

                <select wire:model.live="isRegistered"
                    class="rounded-lg border border-gray-200 px-3 py-2 bg-white text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                    <option value="">All User Status</option>
                    <option value="yes">Already User</option>
                    <option value="no">Not User</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-zinc-700">
                <thead class="bg-gray-50 text-left dark:bg-zinc-800 dark:text-zinc-200">
                    <tr>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">
                            Name</th>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">
                            Email</th>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">
                            Account</th>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">
                            Job Title</th>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">
                            Phone</th>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">
                            Status</th>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">
                            Already User</th>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">
                            Joined</th>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700 bg-white dark:bg-zinc-900">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-gray-50 transition-colors dark:hover:bg-zinc-800/50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">
                                {{ $customer->user->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400">
                                {{ $customer->user->email ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400">
                                {{ $customer->account_number ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400">
                                {{ $customer->job_title ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400">
                                {{ $customer->phone ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($customer->status === 'active') ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                    {{ ucfirst($customer->status ?? 'Active') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $customer->user_id ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400' }}">
                                    {{ $customer->user_id ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400">
                                {{ $customer->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium space-x-2">
                                @if($customer->user)
                                    @can('customers.view')
                                        <a href="{{ route('admin.customers.show', $customer->user) }}"
                                            class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">View</a>
                                    @endcan
                                    @can('customers.edit')
                                        <a href="{{ route('admin.customers.edit', $customer->user) }}"
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</a>
                                    @endcan
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500 dark:text-zinc-400">
                                <flux:icon.users class="w-12 h-12 mb-4 text-gray-300 dark:text-zinc-600" />
                                <p class="text-lg font-medium">No customers found</p>
                                <p class="text-sm">Try adjusting your search terms.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800">
            {{ $customers->links() }}
        </div>
    </div>
</div>