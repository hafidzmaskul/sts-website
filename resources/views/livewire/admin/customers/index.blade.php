<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-black">Customers</h1>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 rounded-2xl shadow border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #AEAEAE;">Total Customers</p>
                    <p class="text-3xl font-bold text-black mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="p-3 rounded-xl" style="background-color: #E6F0FA;">
                    <flux:icon.users class="w-6 h-6" style="color: #0079C2;" />
                </div>
            </div>
        </div>

        <div class="p-6 rounded-2xl shadow border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #AEAEAE;">Active</p>
                    <p class="text-3xl font-bold text-black mt-1">{{ $stats['active'] }}</p>
                </div>
                <div class="p-3 rounded-xl" style="background-color: #ECFDF5;">
                    <flux:icon.check-circle class="w-6 h-6" style="color: #13B469;" />
                </div>
            </div>
        </div>

        <div class="p-6 rounded-2xl shadow border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #AEAEAE;">Suspended</p>
                    <p class="text-3xl font-bold text-black mt-1">{{ $stats['suspended'] }}</p>
                </div>
                <div class="p-3 rounded-xl" style="background-color: #FEF2F2;">
                    <flux:icon.x-circle class="w-6 h-6" style="color: #E02424;" />
                </div>
            </div>
        </div>
    </div>
{{-- //<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"></div> --}}
    <!-- Customers Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Card header: search + filter -->
        <div class="flex flex-col md:flex-row md:items-center gap-3 px-6 py-4 border-b border-gray-100 bg-gray-50">
            <div class="w-full md:flex-1">
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        <!-- Magnifier Icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="7" class="stroke-current"></circle>
                            <path d="M21 21l-3.5-3.5" stroke-linecap="round" class="stroke-current"></path>
                        </svg>
                    </span>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition"
                        placeholder="Search customers..." />
                </div>
            </div>
            <div class="w-full md:w-auto">
                <select wire:model.live="status"
                    class="w-full md:w-auto rounded-lg border border-gray-200 bg-white text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left uppercase text-xs font-semibold tracking-wider text-gray-400">Name</th>
                        <th class="px-6 py-3 text-left uppercase text-xs font-semibold tracking-wider text-gray-400">Email</th>
                        <th class="px-6 py-3 text-left uppercase text-xs font-semibold tracking-wider text-gray-400">Phone</th>
                        <th class="px-6 py-3 text-left uppercase text-xs font-semibold tracking-wider text-gray-400">Status</th>
                        <th class="px-6 py-3 text-left uppercase text-xs font-semibold tracking-wider text-gray-400">Joined</th>
                        <th class="px-6 py-3 text-right uppercase text-xs font-semibold tracking-wider text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($customers as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $user->customer?->phone ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $status = strtolower($user->customer?->status ?? 'active');
                                    $statusMap = [
                                        'active' => ['bg-green-50','text-green-600','dot' => 'bg-green-500'],
                                        'suspended' => ['bg-red-50','text-red-600','dot' => 'bg-red-500'],
                                    ];
                                    $label = ucfirst($status);
                                    $style = $statusMap[$status] ?? ['bg-gray-50', 'text-gray-500', 'dot' => 'bg-gray-400'];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $style[0] }} {{ $style[1] }}">
                                    <span class="w-2 h-2 rounded-full {{ $style['dot'] }} inline-block mr-2"></span>
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex justify-end gap-2">
                                    @can('customers.view')
                                        <a href="{{ route('admin.customers.show', $user) }}"
                                            class="inline-flex items-center rounded-lg bg-gray-50 text-blue-600 hover:bg-blue-50 hover:text-blue-700 p-2 transition"
                                            title="View">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0A9 9 0 11.993 12.007 9 9 0 0121 12z" />
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('customers.edit')
                                        <a href="{{ route('admin.customers.edit', $user) }}"
                                            class="inline-flex items-center rounded-lg bg-gray-50 text-indigo-600 hover:bg-indigo-50 hover:text-indigo-700 p-2 transition"
                                            title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l-10 10M18 2l-2 2m0 0L14 4l4 4m0 0l-4-4M10 10v.01" />
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('customers.delete')
                                        <form action="{{ route('admin.customers.destroy', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Are you sure you want to delete this customer?')"
                                                class="inline-flex items-center rounded-lg bg-gray-50 text-red-600 hover:bg-red-50 hover:text-red-700 p-2 transition"
                                                title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 8v8m4-8v8m4-8v8M3 6h14m-1 0l1-2m-2 2V3a1 1 0 00-1-1h-4a1 1 0 00-1 1v3" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10">
                                <div class="flex flex-col items-center justify-center gap-4">
                                    <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                        <circle cx="24" cy="24" r="22" stroke-width="4" stroke="currentColor" fill="none" />
                                        <path stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M16 30a8 8 0 0116 0"/>
                                        <circle cx="24" cy="18" r="4" fill="currentColor" />
                                    </svg>
                                    <div class="text-lg font-semibold text-gray-500">No customers found</div>
                                    <div class="text-sm text-gray-400">Try adjusting your search or filters.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end">
            {{ $customers->links() }}
        </div>
    </div>
</div>
