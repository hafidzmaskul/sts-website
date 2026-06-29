<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-black">Companies</h1>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 rounded-2xl shadow border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #AEAEAE;">Total Companies</p>
                    <p class="text-3xl font-bold text-black mt-1">{{ $stats['total_companies'] }}</p>
                </div>
                <div class="p-3 rounded-xl" style="background-color: #E6F0FA;">
                    <flux:icon.building-office class="w-6 h-6" style="color: #0079C2;" />
                </div>
            </div>
        </div>

        <div class="p-6 rounded-2xl shadow border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #AEAEAE;">Total Employees</p>
                    <p class="text-3xl font-bold text-black mt-1">{{ $stats['total_employees'] }}</p>
                </div>
                <div class="p-3 rounded-xl" style="background-color: #ECFDF5;">
                    <flux:icon.users class="w-6 h-6" style="color: #13B469;" />
                </div>
            </div>
        </div>

        <div class="p-6 rounded-2xl shadow border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #AEAEAE;">Pending Reviews</p>
                    <p class="text-3xl font-bold text-black mt-1">{{ $stats['pending_reviews'] }}</p>
                </div>
                <div class="p-3 rounded-xl" style="background-color: #FEF9C3;">
                    <flux:icon.clock class="w-6 h-6" style="color: #CA8A04;" />
                </div>
            </div>
        </div>
    </div>

    <!-- Companies Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Card header: search -->
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <div class="flex flex-col md:flex-row md:items-end gap-4">
                <!-- Search -->
                <div class="w-full md:flex-1">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                            <flux:icon.magnifying-glass class="w-5 h-5" />
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition text-black"
                            placeholder="Search company name, reg number..." />
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-black w-16">#</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-black">Name</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-black">Registration No
                        </th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-black">Contact Email</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-black">Role</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-black">Customers</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-black">Status</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-black">Created At</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-black">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($companies as $company)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                {{ ($companies->currentPage() - 1) * $companies->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-black">
                                {{ $company->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-black">
                                {{ $company->registration_number ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-black">
                                {{ $company->purchasing_contact_email ?? ($company->accounts_contact_email ?? '-') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-black">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $company->requested_credit_limit ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $company->requested_credit_limit ? 'Credit Facilities' : 'Trade Account' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-black">
                                <div class="flex flex-col gap-1">
                                    @foreach($company->customers as $customer)
                                        <div class="text-sm">
                                            {{ $customer->first_name }} {{ $customer->last_name }}
                                        </div>
                                    @endforeach
                                    @if($company->customers->isEmpty())
                                        -
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1">
                                    @foreach($company->customers as $customer)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium w-fit
                                                                    @if($customer->status_review === 'approved') bg-green-100 text-green-800
                                                                    @elseif($customer->status_review === 'declined') bg-red-100 text-red-800
                                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($customer->status_review) }}
                                        </span>
                                    @endforeach
                                    @if($company->customers->isEmpty())
                                        -
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-black">
                                {{ $company->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium space-x-2">
                                <a href="{{ route('admin.companies.show', $company->id) }}" class="text-black hover:text-indigo-600 transition-colors inline-flex" title="View details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-black">
                                    <flux:icon.building-office class="w-12 h-12 mb-4 text-gray-300" />
                                    <p class="text-lg font-medium">No companies found</p>
                                    <p class="text-sm">Try adjusting your search terms.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end overflow-x-auto">
            {{ $companies->links() }}
        </div>
    </div>
</div>