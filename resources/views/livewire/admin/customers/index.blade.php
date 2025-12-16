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

    <!-- Search -->
    <!-- Filters -->
    <div class="flex items-center gap-3">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search customers..."
            class="w-full md:w-80 rounded-lg border px-3 py-2"
            style="border: 1px solid #AEAEAE; color: #000; background-color: transparent;"
            placeholder="Search customers..." onfocus="this.placeholder=''" onblur="this.placeholder='Search customers...'">

        <select wire:model.live="status"
            class="rounded-lg border px-3 py-2"
            style="border: 1px solid #AEAEAE; color: #000; background-color: transparent;">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="suspended">Suspended</option>
        </select>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-xl border">
        <table class="min-w-full text-sm">
            <thead style="background: #fff;">
                <tr>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color:#AEAEAE;">Name</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color:#AEAEAE;">Email</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color:#AEAEAE;">Phone</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color:#AEAEAE;">Status</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color:#AEAEAE;">Joined</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color:#AEAEAE;">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($customers as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-black">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-black">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-black">
                            {{ $user->customer?->phone ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                style="
                                    {{ ($user->customer?->status === 'active')
                                    ? 'border: 1px solid #13B469; color: #13B469; background: transparent;'
                                    : 'border: 1px solid #E02424; color: #E02424; background: transparent;' }}">
                                {{ ucfirst($user->customer?->status ?? 'Active') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-black">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium space-x-2 flex items-center">
                            @can('customers.view')
                                <a href="{{ route('admin.customers.show', $user) }}"
                                    class="border border-[#0079C2] text-[#0079C2] px-2 py-1 rounded-lg bg-white hover:bg-[#0079C2] hover:text-white transition">View</a>
                            @endcan
                            @can('customers.edit')
                                <a href="{{ route('admin.customers.edit', $user) }}"
                                    class="flex items-center justify-center border border-[#0079C2] text-[#0079C2] px-2 py-1 rounded-lg bg-white hover:bg-[#0079C2] hover:text-white transition" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 1200 1200" style="color:#000000;">
                                        <path fill="currentColor" d="M0 0v1200h1200V424.292l-196.875 196.875v381.958h-806.25v-806.25h381.958L775.708 0zm1050 0l-76.831 76.831l150 150L1200 150zM936.914 113.086L497.168 552.832l150 150l439.746-439.746zM441.943 622.339c-2.225.034-4.493.195-6.738.366v142.09h142.09c0-38.708-18.492-78.039-47.314-105.542c-23.842-22.751-54.675-37.428-88.038-36.914"></path>
                                    </svg>
                                </a>
                            @endcan
                            @can('customers.delete')
                                <form action="{{ route('admin.customers.destroy', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center justify-center border border-[#E02424] text-[#E02424] px-2 py-1 rounded-lg bg-white hover:bg-[#E02424] hover:text-white transition" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 12 12" style="color:#000000;">
                                            <path fill="none" stroke="currentColor" stroke-linecap="round" d="M2 2.5h8" stroke-width="1"></path>
                                            <path fill="currentColor" d="M2 4v7c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4zm3 5.5c0 .28-.22.5-.5.5S4 9.78 4 9.5V6c0-.28.22-.5.5-.5s.5.22.5.5zm3 0c0 .28-.22.5-.5.5S7 9.78 7 9.5V6c0-.28.22-.5.5-.5s.5.22.5.5zM8 3H4V1c0-.55.45-1 1-1h2c.55 0 1 .45 1 1z"></path>
                                        </svg>
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center" style="color:#AEAEAE;">No customers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $customers->links() }}
    </div>
</div>
