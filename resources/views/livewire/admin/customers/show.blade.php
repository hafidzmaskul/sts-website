<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-black">
                    {{ $customer->user_id ? $customer->user->name : $customer->first_name . ' ' . $customer->last_name }}
                </h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                    @if($customer->status_review === 'approved') bg-green-100 text-green-800
                    @elseif($customer->status_review === 'declined') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ ucfirst($customer->status_review) }}
                </span>
            </div>
            <p class="text-sm text-black mt-1">
                {{ $customer->user_id ? $customer->user->email : $customer->email }}
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.customers.index') }}"
                class="px-4 py-2 border border-gray-700 rounded-lg text-black bg-white hover:bg-gray-50 flex items-center justify-center">
                Back to List
            </a>

            @if($customer->status_review === 'pending')
                @can('customers.edit')
                    <button wire:click="approve"
                        onclick="confirm('Are you sure you want to approve this customer? This will create a user account.') || event.stopImmediatePropagation()"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Approve
                    </button>
                    <button wire:click="confirmDecline" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Decline
                    </button>
                @endcan
            @endif

            @if($customer->user_id)
                @can('customers.edit')
                    <a href="{{ route('admin.customers.edit', $customer->id) }}"
                        class="flex items-center px-2 py-2 rounded-lg border border-black bg-white hover:bg-gray-100 text-black"
                        style="height: 36px; width: 36px; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                            viewBox="0 0 1200 1200">
                            <path
                                d="M0 0v1200h1200V424.292l-196.875 196.875v381.958h-806.25v-806.25h381.958L775.708 0zm1050 0l-76.831 76.831l150 150L1200 150zM936.914 113.086L497.168 552.832l150 150l439.746-439.746zM441.943 622.339c-2.225.034-4.493.195-6.738.366v142.09h142.09c0-38.708-18.492-78.039-47.314-105.542c-23.842-22.751-54.675-37.428-88.038-36.914">
                            </path>
                        </svg>
                    </a>
                @endcan
            @endif
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Application & Personal Details -->
        <div class="space-y-6">
            <div class="rounded-xl shadow p-6 border border-gray-200 bg-white">
                <h2 class="text-lg font-semibold mb-4 text-black flex items-center gap-2">
                    <flux:icon.user class="w-5 h-5 text-gray-400" />
                    Personal & Application Info
                </h2>
                <dl class="space-y-3">
                    <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                        <dd class="col-span-2 text-sm text-black font-medium">
                            {{ $customer->first_name }} {{ $customer->last_name }}
                        </dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="col-span-2 text-sm text-black">
                            {{ $customer->email }}
                        </dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                        <dt class="text-sm font-medium text-gray-500">Job Title</dt>
                        <dd class="col-span-2 text-sm text-black">
                            {{ $customer->job_title ?? '-' }}
                        </dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                        <dt class="text-sm font-medium text-gray-500">Role Applied</dt>
                        <dd class="col-span-2 text-sm text-black font-semibold">
                            {{ ucfirst($customer->role_applied) ?? '-' }}
                        </dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                        <dt class="text-sm font-medium text-gray-500">Application Date</dt>
                        <dd class="col-span-2 text-sm text-black">
                            {{ $customer->created_at->format('M d, Y H:i') }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Shipping Addresses -->
            <div class="rounded-xl shadow p-6 border border-gray-200 bg-white">
                <h2 class="text-lg font-semibold mb-4 text-black flex items-center gap-2">
                    <flux:icon.truck class="w-5 h-5 text-gray-400" />
                    Shipping Addresses
                </h2>
                @if($customer->shippingAddresses->count() > 0)
                    <div class="space-y-4">
                        @foreach($customer->shippingAddresses as $address)
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                <h3 class="text-sm font-semibold text-black mb-1">
                                    {{ $address->first_name }} {{ $address->last_name }}
                                </h3>
                                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $address->address }}</p>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $address->city }}, {{ $address->postal_code }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $address->country }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">No shipping addresses found.</p>
                @endif
            </div>

            <!-- Company Details -->
            <div class="rounded-xl shadow p-6 border border-gray-200 bg-white">
                <h2 class="text-lg font-semibold mb-4 text-black flex items-center gap-2">
                    <flux:icon.building-office class="w-5 h-5 text-gray-400" />
                    Company Details
                </h2>
                @if($customer->company)
                    <dl class="space-y-3">
                        <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                            <dt class="text-sm font-medium text-gray-500">Company Name</dt>
                            <dd class="col-span-2 text-sm text-black font-semibold">{{ $customer->company->name }}</dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                            <dt class="text-sm font-medium text-gray-500">Reg Number</dt>
                            <dd class="col-span-2 text-sm text-black">{{ $customer->company->registration_number ?? '-' }}
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                            <dt class="text-sm font-medium text-gray-500">Trading Name</dt>
                            <dd class="col-span-2 text-sm text-black">{{ $customer->company->trading_name ?? '-' }}</dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                            <dt class="text-sm font-medium text-gray-500">VAT Number</dt>
                            <dd class="col-span-2 text-sm text-black">{{ $customer->company->vat_number ?? '-' }}</dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                            <dt class="text-sm font-medium text-gray-500">Phone / Fax</dt>
                            <dd class="col-span-2 text-sm text-black">
                                <div>{{ $customer->company->phone ?? '-' }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $customer->company->fax ? 'Fax: ' . $customer->company->fax : '' }}</div>
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                            <dt class="text-sm font-medium text-gray-500">Address</dt>
                            <dd class="col-span-2 text-sm text-black">
                                <div class="whitespace-pre-line">{{ $customer->company->address ?? '-' }}</div>
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                            <dt class="text-sm font-medium text-gray-500">Trading Address</dt>
                            <dd class="col-span-2 text-sm text-black">
                                <div class="whitespace-pre-line">{{ $customer->company->trading_address ?? '-' }}</div>
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                            <dt class="text-sm font-medium text-gray-500">Activities</dt>
                            <dd class="col-span-2 text-sm text-black">
                                {{ $customer->company->activities_description ?? '-' }}
                            </dd>
                        </div>
                    </dl>
                @else
                    <p class="text-sm text-gray-500 italic">No company details linked.</p>
                @endif
            </div>
        </div>

        <!-- Contacts & Financial -->
        <div class="space-y-6">
            <!-- Key Contacts -->
            <div class="rounded-xl shadow p-6 border border-gray-200 bg-white">
                <h2 class="text-lg font-semibold mb-4 text-black flex items-center gap-2">
                    <flux:icon.users class="w-5 h-5 text-gray-400" />
                    Key Contacts
                </h2>
                @if($customer->company)
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-2">Purchasing Contact</h3>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Name</dt>
                                    <dd class="text-sm font-medium text-black">
                                        {{ $customer->company->purchasing_contact_name ?? '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Phone</dt>
                                    <dd class="text-sm text-black">{{ $customer->company->purchasing_contact_phone ?? '-' }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Email</dt>
                                    <dd class="text-sm text-black">{{ $customer->company->purchasing_contact_email ?? '-' }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        <div class="border-t border-gray-50 pt-4">
                            <h3 class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-2">Accounts Contact</h3>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Name</dt>
                                    <dd class="text-sm font-medium text-black">
                                        {{ $customer->company->accounts_contact_name ?? '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Phone</dt>
                                    <dd class="text-sm text-black">{{ $customer->company->accounts_contact_phone ?? '-' }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Email</dt>
                                    <dd class="text-sm text-black">{{ $customer->company->accounts_contact_email ?? '-' }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">No contact details linked.</p>
                @endif
            </div>

            <!-- Financial Information -->
            <div class="rounded-xl shadow p-6 border border-gray-200 bg-white">
                <h2 class="text-lg font-semibold mb-4 text-black flex items-center gap-2">
                    <flux:icon.banknotes class="w-5 h-5 text-gray-400" />
                    Financial & Trade Refs
                </h2>
                @if($customer->company)
                    <dl class="space-y-3">
                        <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                            <dt class="text-sm font-medium text-gray-500">Bank Name</dt>
                            <dd class="col-span-2 text-sm text-black font-medium">{{ $customer->company->bank_name ?? '-' }}
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                            <dt class="text-sm font-medium text-gray-500">Account No</dt>
                            <dd class="col-span-2 text-sm text-black">{{ $customer->company->bank_account_number ?? '-' }}
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                            <dt class="text-sm font-medium text-gray-500">Sort Code</dt>
                            <dd class="col-span-2 text-sm text-black">{{ $customer->company->bank_sort_code ?? '-' }}</dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4 pb-3 border-b border-gray-50 last:border-0 last:pb-0">
                            <dt class="text-sm font-medium text-gray-500">Req. Credit Limit</dt>
                            <dd class="col-span-2 text-sm text-black font-semibold">
                                {{ $customer->company->requested_credit_limit ? number_format($customer->company->requested_credit_limit, 2) : '-' }}
                            </dd>
                        </div>
                    </dl>

                    <div class="mt-6 space-y-4">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <h3 class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-1">Trade Reference 1</h3>
                            <p class="text-sm font-medium text-black">{{ $customer->company->trade_ref_1_details ?? '-' }}
                            </p>
                            <div class="text-xs text-gray-500 mt-1 flex gap-3">
                                <span>{{ $customer->company->trade_ref_1_phone ?? '' }}</span>
                                <span>{{ $customer->company->trade_ref_1_email ?? '' }}</span>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <h3 class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-1">Trade Reference 2</h3>
                            <p class="text-sm font-medium text-black">{{ $customer->company->trade_ref_2_details ?? '-' }}
                            </p>
                            <div class="text-xs text-gray-500 mt-1 flex gap-3">
                                <span>{{ $customer->company->trade_ref_2_phone ?? '' }}</span>
                                <span>{{ $customer->company->trade_ref_2_email ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">No financial details linked.</p>
                @endif
            </div>

            @if($customer->status_review === 'declined')
                <div class="rounded-xl shadow p-6 border border-red-200 bg-red-50">
                    <h2 class="text-lg font-semibold mb-2 text-red-800 flex items-center gap-2">
                        <flux:icon.x-circle class="w-5 h-5 text-red-600" />
                        Declined Reason
                    </h2>
                    <p class="text-sm text-red-700">{{ $customer->review_note }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Decline Modal -->
    @if($showDeclineModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
                <h3 class="text-lg font-semibold text-black mb-4">Decline Application</h3>
                <p class="text-sm text-black mb-4">Please provide a reason for declining this application.</p>

                <textarea wire:model="reviewNote" rows="4"
                    class="w-full rounded-lg border border-gray-300 p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-black"
                    placeholder="Enter reason here..."></textarea>
                @error('reviewNote') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                <div class="flex justify-end gap-3 mt-4">
                    <button wire:click="$set('showDeclineModal', false)"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-black hover:bg-gray-50">
                        Cancel
                    </button>
                    <button wire:click="decline" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Confirm Decline
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>