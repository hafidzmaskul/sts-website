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
                    <p class="text-sm font-medium" style="color: #AEAEAE;">Pending Review</p>
                    <p class="text-3xl font-bold text-black mt-1">{{ $stats['pending'] }}</p>
                </div>
                <div class="p-3 rounded-xl" style="background-color: #FEF9C3;">
                    <flux:icon.clock class="w-6 h-6" style="color: #CA8A04;" />
                </div>
            </div>
        </div>
    </div>

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
                    <input type="text" wire:model.live.debounce.300ms="search"
                        class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition text-black"
                        placeholder="Search customers..." />
                </div>
            </div>
            <div class="w-full md:w-auto flex gap-2">
                <select wire:model.live="statusReview"
                    class="w-full md:w-auto rounded-lg border border-gray-200 bg-white text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition text-black">
                    <option value="">All Review Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="declined">Declined</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-black">
                            Name</th>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-black">
                            Email</th>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-black">
                            Review Status</th>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-black">
                            Role Applied</th>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-black">
                            Job Title</th>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-black">
                            Phone</th>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-black">
                            Joined</th>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-black">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-black">
                                {{ $customer->user_id ? $customer->user->name : ($customer->first_name . ' ' . $customer->last_name) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-black">
                                {{ $customer->user_id ? $customer->user->email : $customer->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($customer->status_review === 'approved') bg-green-100 text-green-800
                                        @elseif($customer->status_review === 'declined') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($customer->status_review) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-black">
                                {{ ucfirst($customer->role_applied) ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-black">
                                {{ $customer->job_title ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-black">
                                {{ $customer->phone ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-black">
                                {{ $customer->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium space-x-2">
                                @can('customers.view')
                                    <a href="{{ route('admin.customers.show', $customer->id) }}"
                                        class="text-black hover:text-gray-900">View</a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-black">
                                    <flux:icon.users class="w-12 h-12 mb-4 text-gray-300" />
                                    <p class="text-lg font-medium">No customers found</p>
                                    <p class="text-sm">Try adjusting your search terms.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end">
            {{ $customers->links() }}
        </div>
    </div>
</div>
