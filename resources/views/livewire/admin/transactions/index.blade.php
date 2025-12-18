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
    <div
        class="bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-zinc-900 dark:border-zinc-700 overflow-hidden">
        <!-- Search & Filters -->
        <div
            class="p-4 border-b border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900/50 flex flex-col md:flex-row gap-4 justify-between">
            <div class="relative max-w-md w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <flux:icon.magnifying-glass class="w-5 h-5 text-gray-400" />
                </div>
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Search by Invoice or Customer..."
                    class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <select wire:model.live="status"
                    class="rounded-lg border border-gray-200 px-3 py-2 bg-white text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
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
            <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-zinc-700">
                <thead class="bg-gray-50 text-left dark:bg-zinc-800 dark:text-zinc-200">
                    <tr>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">
                            Invoice Code</th>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">
                            Customer</th>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">
                            Total Amount</th>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">
                            Status</th>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">
                            Date</th>
                        <th
                            class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500 dark:text-zinc-400">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700 bg-white dark:bg-zinc-900">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50 transition-colors dark:hover:bg-zinc-800/50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">
                                {{ $transaction->invoice_code }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400">
                                {{ $transaction->customer->user->name ?? 'Guest' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white font-bold">
                                ${{ number_format($transaction->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($transaction->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                        @elseif($transaction->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                        @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400">
                                {{ $transaction->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">
                                <a href="{{ route('admin.transactions.show', $transaction) }}"
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-zinc-400">
                                No transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800">
            {{ $transactions->links() }}
        </div>
    </div>
</div>