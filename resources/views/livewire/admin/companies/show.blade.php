<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-black">{{ $company->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">Registration No: {{ $company->registration_number ?? '-' }}</p>
        </div>
        <div>
            <a href="{{ route('admin.companies.index') }}"
                class="px-4 py-2 border border-gray-700 rounded-lg text-black bg-white hover:bg-gray-50 flex items-center justify-center">
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Company Info -->
        <div class="space-y-6">
            <div class="rounded-xl shadow p-6 border border-gray-200 bg-white">
                <h2 class="text-lg font-semibold mb-4 text-black flex items-center gap-2">
                    <flux:icon.building-office class="w-5 h-5 text-gray-400" />
                    Company Information
                </h2>
                <dl class="space-y-3">
                    <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                        <dt class="text-sm font-medium text-gray-500">Trading Name</dt>
                        <dd class="col-span-2 text-sm text-black">{{ $company->trading_name ?? '-' }}</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                        <dt class="text-sm font-medium text-gray-500">VAT Number</dt>
                        <dd class="col-span-2 text-sm text-black">{{ $company->vat_number ?? '-' }}</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="col-span-2 text-sm text-black">{{ $company->phone ?? '-' }}</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                        <dt class="text-sm font-medium text-gray-500">Fax</dt>
                        <dd class="col-span-2 text-sm text-black">{{ $company->fax ?? '-' }}</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="col-span-2 text-sm text-black whitespace-pre-line">{{ $company->address ?? '-' }}
                        </dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                        <dt class="text-sm font-medium text-gray-500">Trading Address</dt>
                        <dd class="col-span-2 text-sm text-black whitespace-pre-line">
                            {{ $company->trading_address ?? '-' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Financial Info -->
            <div class="rounded-xl shadow p-6 border border-gray-200 bg-white">
                <h2 class="text-lg font-semibold mb-4 text-black flex items-center gap-2">
                    <flux:icon.banknotes class="w-5 h-5 text-gray-400" />
                    Financial Details
                </h2>
                <dl class="space-y-3">
                    <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                        <dt class="text-sm font-medium text-gray-500">Bank Name</dt>
                        <dd class="col-span-2 text-sm text-black">{{ $company->bank_name ?? '-' }}</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                        <dt class="text-sm font-medium text-gray-500">Account No</dt>
                        <dd class="col-span-2 text-sm text-black">{{ $company->bank_account_number ?? '-' }}</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                        <dt class="text-sm font-medium text-gray-500">Sort Code</dt>
                        <dd class="col-span-2 text-sm text-black">{{ $company->bank_sort_code ?? '-' }}</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                        <dt class="text-sm font-medium text-gray-500">Credit Limit</dt>
                        <dd class="col-span-2 text-sm font-semibold text-black">
                            {{ $company->requested_credit_limit ? number_format($company->requested_credit_limit, 2) : '-' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Credit Limits -->
            <div class="rounded-xl shadow p-6 border border-gray-200 bg-white">
                <h2 class="text-lg font-semibold mb-4 text-black flex items-center gap-2">
                    <flux:icon.credit-card class="w-5 h-5 text-gray-400" />
                    Credit Limits
                </h2>

                @if($company->creditLimits->count() > 0)
                    <div class="overflow-hidden border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date</th>
                                    <th scope="col"
                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Description</th>
                                    <th scope="col"
                                        class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Credit</th>
                                    <th scope="col"
                                        class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Debit</th>
                                    <th scope="col"
                                        class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Balance</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($company->creditLimits as $limit)
                                    <tr>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
                                            {{ $limit->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-3 py-2 text-sm text-gray-900">
                                            {{ $limit->description ?? '-' }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-green-600 font-medium">
                                            {{ number_format($limit->credit, 2) }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-red-600 font-medium">
                                            {{ number_format($limit->debit, 2) }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-gray-900 font-bold">
                                            {{ number_format($limit->balance, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">No credit limit history found.</p>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <!-- Key Contacts -->
            <div class="rounded-xl shadow p-6 border border-gray-200 bg-white">
                <h2 class="text-lg font-semibold mb-4 text-black flex items-center gap-2">
                    <flux:icon.users class="w-5 h-5 text-gray-400" />
                    Key Contacts
                </h2>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-2">Purchasing Contact</h3>
                        <dl class="space-y-2">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Name</dt>
                                <dd class="text-sm font-medium text-black">
                                    {{ $company->purchasing_contact_name ?? '-' }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Email</dt>
                                <dd class="text-sm text-black">{{ $company->purchasing_contact_email ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Phone</dt>
                                <dd class="text-sm text-black">{{ $company->purchasing_contact_phone ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                    <div class="border-t border-gray-50 pt-4">
                        <h3 class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-2">Accounts Contact</h3>
                        <dl class="space-y-2">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Name</dt>
                                <dd class="text-sm font-medium text-black">{{ $company->accounts_contact_name ?? '-' }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Email</dt>
                                <dd class="text-sm text-black">{{ $company->accounts_contact_email ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Phone</dt>
                                <dd class="text-sm text-black">{{ $company->accounts_contact_phone ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Associated Customers -->
            <div class="rounded-xl shadow p-6 border border-gray-200 bg-white">
                <h2 class="text-lg font-semibold mb-4 text-black flex items-center gap-2">
                    <flux:icon.users class="w-5 h-5 text-gray-400" />
                    Associated Employees ({{ $company->customers->count() }})
                </h2>
                @if($company->customers->count() > 0)
                    <div class="flow-root">
                        <ul role="list" class="-my-5 divide-y divide-gray-200">
                            @foreach($company->customers as $customer)
                                <li class="py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $customer->first_name }} {{ $customer->last_name }}
                                            </p>
                                            <p class="text-sm text-gray-500 truncate">
                                                {{ $customer->email }}
                                            </p>
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.customers.show', $customer->id) }}"
                                                class="inline-flex items-center shadow-sm px-2.5 py-0.5 border border-gray-300 text-sm leading-5 font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">No employees linked to this company.</p>
                @endif
            </div>
        </div>
    </div>
</div>