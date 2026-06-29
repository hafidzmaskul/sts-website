<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-black">Transactions</h1>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 rounded-2xl shadow border bg-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #AEAEAE;">Total Transactions</p>
                    <p class="text-3xl font-bold text-black mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="p-3 rounded-xl" style="background-color: #E6F0FA;">
                    <flux:icon.banknotes class="w-6 h-6" style="color: #0079C2;" />
                </div>
            </div>
        </div>

        <div class="p-6 rounded-2xl shadow border bg-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #AEAEAE;">Pending</p>
                    <p class="text-3xl font-bold text-black mt-1">{{ $stats['pending'] }}</p>
                </div>
                <div class="p-3 rounded-xl" style="background-color: #FEF3C7;">
                    <flux:icon.clock class="w-6 h-6" style="color: #D97706;" />
                </div>
            </div>
        </div>

        <div class="p-6 rounded-2xl shadow border bg-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #AEAEAE;">Completed</p>
                    <p class="text-3xl font-bold text-black mt-1">{{ $stats['completed'] }}</p>
                </div>
                <div class="p-3 rounded-xl" style="background-color: #ECFDF5;">
                    <flux:icon.check-circle class="w-6 h-6" style="color: #13B469;" />
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Search & Filters -->
        <div class="p-4 border-b border-gray-200 bg-gray-50 flex flex-col md:flex-row gap-4 justify-between">
            <div class="relative max-w-md w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <flux:icon.magnifying-glass class="w-5 h-5 text-gray-400" />
                </div>
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Search by Invoice, Order Code or Customer..."
                    class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-btn-primary-ring focus:border-indigo-500 sm:text-sm text-black">
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <select wire:model.live="status"
                    class="rounded-lg border border-gray-200 px-3 py-2 bg-white text-sm focus:ring-btn-primary-ring focus:border-indigo-500 text-black">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">
                            Invoice Code</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">
                            Order Code</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">
                            Customer</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">
                            Total Amount</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">
                            Status</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">
                            Date</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-black">
                                {{ $transaction->invoice_code }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-black">
                                {{ $transaction->order_code ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-black">
                                {{ $transaction->customer->user->name ?? 'Guest' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-black font-bold">
                                £{{ number_format($transaction->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($transaction->status === 'completed') bg-green-100 text-green-800
                                            @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($transaction->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-black">
                                {{ $transaction->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">
                                <a href="{{ route('admin.transactions.show', $transaction) }}" class="text-black hover:text-indigo-600 transition-colors inline-flex" title="View details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-black">
                                No transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 overflow-x-auto">
            {{ $transactions->links() }}
        </div>
    </div>
</div>