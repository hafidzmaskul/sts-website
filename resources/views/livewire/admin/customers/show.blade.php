<div class="p-6 space-y-6 dark:bg-zinc-900">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                <span
                    class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($user->customer?->status === 'active') ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                    {{ ucfirst($user->customer?->status ?? 'Active') }}
                </span>
            </div>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">{{ $user->email }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.customers.index') }}"
                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800">
                Back to List
            </a>
            @can('customers.edit')
                <a href="{{ route('admin.customers.edit', $user->id) }}"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Edit Customer
                </a>
            @endcan
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Profile Details -->
        <div class="bg-white rounded-xl shadow p-6 dark:bg-zinc-900 dark:border dark:border-zinc-700">
            <h2 class="text-lg font-semibold mb-4 dark:text-white">Profile Information</h2>
            <dl class="grid grid-cols-1 gap-4">
                <div class="grid grid-cols-3 gap-4">
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
                        {{ $user->customer?->phone ?? '-' }}
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">Address</dt>
                    <dd class="col-span-2 text-sm text-gray-900 dark:text-zinc-200">
                        {{ $user->customer?->address ?? '-' }}
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">City</dt>
                    <dd class="col-span-2 text-sm text-gray-900 dark:text-zinc-200">
                        {{ $user->customer?->city ?? '-' }}
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">Postal Code</dt>
                    <dd class="col-span-2 text-sm text-gray-900 dark:text-zinc-200">
                        {{ $user->customer?->postal_code ?? '-' }}
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">Country</dt>
                    <dd class="col-span-2 text-sm text-gray-900 dark:text-zinc-200">
                        {{ $user->customer?->country ?? '-' }}
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">Joined Date</dt>
                    <dd class="col-span-2 text-sm text-gray-900 dark:text-zinc-200">
                        {{ $user->created_at->format('M d, Y H:i') }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>