<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold" style="color: #000;">{{ $user->name }}</h1>
                <span
                    class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($user->customer?->status === 'active') ? 'border border-green-800 text-green-800' : 'border border-red-800 text-red-800' }}">
                    {{ ucfirst($user->customer?->status ?? 'Active') }}
                </span>
            </div>
            <p class="text-sm" style="color: #AEAEAE; margin-top: 0.25rem;">{{ $user->email }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.customers.index') }}"
                class="px-4 py-2 border border-gray-700 rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                Back to List
            </a>
            @can('customers.edit')
                <a href="{{ route('admin.customers.edit', $user->id) }}"
                    class="flex items-center px-2 py-2 rounded-lg border border-black bg-white hover:bg-gray-100 text-black"
                    style="height: 36px; width: 36px; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" style="color: #000000;" viewBox="0 0 1200 1200">
                        <path fill="currentColor" d="M0 0v1200h1200V424.292l-196.875 196.875v381.958h-806.25v-806.25h381.958L775.708 0zm1050 0l-76.831 76.831l150 150L1200 150zM936.914 113.086L497.168 552.832l150 150l439.746-439.746zM441.943 622.339c-2.225.034-4.493.195-6.738.366v142.09h142.09c0-38.708-18.492-78.039-47.314-105.542c-23.842-22.751-54.675-37.428-88.038-36.914"></path>
                    </svg>
                </a>
            @endcan
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Profile Details -->
        <div class="rounded-xl shadow p-6 border border-gray-200">
            <h2 class="text-lg font-semibold mb-4" style="color: #AEAEAE;">Profile Information</h2>
            <dl class="grid grid-cols-1 gap-4">
                <div class="grid grid-cols-3 gap-4">
<<<<<<< HEAD
                    <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">Account Number</dt>
                    <dd class="col-span-2 text-sm text-gray-900 dark:text-zinc-200">
                        {{ $user->customer?->account_number ?? '-' }}
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">Job Title</dt>
                    <dd class="col-span-2 text-sm text-gray-900 dark:text-zinc-200">
                        {{ $user->customer?->job_title ?? '-' }}
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">Phone</dt>
                    <dd class="col-span-2 text-sm text-gray-900 dark:text-zinc-200">
=======
                    <dt class="text-sm font-medium" style="color: #AEAEAE;">Phone</dt>
                    <dd class="col-span-2 text-sm" style="color: #000;">
>>>>>>> 2f91164598756df8c77d35af2cf702c8f18e17bd
                        {{ $user->customer?->phone ?? '-' }}
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium" style="color: #AEAEAE;">Address</dt>
                    <dd class="col-span-2 text-sm" style="color: #000;">
                        {{ $user->customer?->address ?? '-' }}
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium" style="color: #AEAEAE;">City</dt>
                    <dd class="col-span-2 text-sm" style="color: #000;">
                        {{ $user->customer?->city ?? '-' }}
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium" style="color: #AEAEAE;">Postal Code</dt>
                    <dd class="col-span-2 text-sm" style="color: #000;">
                        {{ $user->customer?->postal_code ?? '-' }}
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium" style="color: #AEAEAE;">Country</dt>
                    <dd class="col-span-2 text-sm" style="color: #000;">
                        {{ $user->customer?->country ?? '-' }}
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium" style="color: #AEAEAE;">Joined Date</dt>
                    <dd class="col-span-2 text-sm" style="color: #000;">
                        {{ $user->created_at->format('M d, Y H:i') }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>
