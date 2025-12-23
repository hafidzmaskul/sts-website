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
            <p class="text-sm text-gray-400 mt-1">
                {{ $customer->user_id ? $customer->user->email : $customer->email }}
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.customers.index') }}"
                class="px-4 py-2 border border-gray-700 rounded-lg text-gray-700 bg-white hover:bg-gray-50 flex items-center justify-center">
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
        <!-- Profile Details -->
        <div class="rounded-xl shadow p-6 border border-gray-200 bg-white">
            <h2 class="text-lg font-semibold mb-4 text-gray-400">Application Information</h2>
            <dl class="grid grid-cols-1 gap-4">
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Role Applied</dt>
                    <dd class="col-span-2 text-sm text-gray-900 font-semibold">
                        {{ ucfirst($customer->role_applied) ?? '-' }}
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Pricing Formula</dt>
                    <dd class="col-span-2 text-sm text-gray-900">
                        @if($customer->user && $customer->user->pricingFormula)
                            <div class="font-medium">{{ $customer->user->pricingFormula->label }}</div>
                        @else
                            -
                        @endif
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Account Number</dt>
                    <dd class="col-span-2 text-sm text-gray-900">
                        {{ $customer->account_number ?? '-' }}
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Job Title</dt>
                    <dd class="col-span-2 text-sm text-gray-900">
                        {{ $customer->job_title ?? '-' }}
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                    <dd class="col-span-2 text-sm text-gray-900">
                        {{ $customer->phone ?? '-' }}
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">City</dt>
                    <dd class="col-span-2 text-sm text-gray-900">
                        {{ $customer->city ?? '-' }}
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                    <dd class="col-span-2 text-sm text-gray-900">
                        {{ $customer->address ?? '-' }}
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500">Joined Date</dt>
                    <dd class="col-span-2 text-sm text-gray-900">
                        {{ $customer->created_at->format('M d, Y H:i') }}
                    </dd>
                </div>

                @if($customer->status_review === 'declined')
                    <div class="grid grid-cols-3 gap-4 mt-4 pt-4 border-t border-gray-100">
                        <dt class="text-sm font-medium text-red-600">Decline Reason</dt>
                        <dd class="col-span-2 text-sm text-red-600">
                            {{ $customer->review_note }}
                        </dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Decline Modal -->
    @if($showDeclineModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Decline Application</h3>
                <p class="text-sm text-gray-500 mb-4">Please provide a reason for declining this application.</p>

                <textarea wire:model="reviewNote" rows="4"
                    class="w-full rounded-lg border border-gray-300 p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter reason here..."></textarea>
                @error('reviewNote') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                <div class="flex justify-end gap-3 mt-4">
                    <button wire:click="$set('showDeclineModal', false)"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
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