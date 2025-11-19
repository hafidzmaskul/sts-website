<div>
    <header class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur dark:border-zinc-700 dark:bg-zinc-800/50">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold dark:text-white">
                Transactions
            </h1>
        </div>
    </header>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-12 sm:px-6 lg:px-8">
        
        <div class="flex items-center gap-3">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Search by invoice, name, or email..."
                class="w-full md:w-80 rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
            />
        </div>

        <div class="overflow-x-auto rounded-xl border dark:border-zinc-700 dark:bg-zinc-800">
            <table class="min-w-full text-sm">
                <thead class="bg-zinc-50 text-left dark:bg-zinc-900 dark:text-zinc-200">
                    <tr>
                        <th class="px-4 py-3">Invoice</th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3 w-20">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr class="border-t dark:border-zinc-700">
                            <td class="px-4 py-3 font-medium dark:text-white">
                                {{ $transaction->invoice_code }}
                            </td>
                            <td class="px-4 py-3 dark:text-zinc-300">
                                <div class="flex flex-col">
                                    <span class="font-medium">{{ $transaction->full_name }}</span>
                                    <span class="text-xs text-zinc-500">{{ $transaction->email }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 dark:text-zinc-300">
                                £{{ number_format($transaction->total_amount, 2) }}
                            </td>
                            <td class="px-4 py-3">
                                @if($transaction->status === 'paid')
                                    <span class="px-2 py-0.5 rounded border text-xs text-green-700 border-green-400 bg-green-100 dark:bg-green-900 dark:border-green-700 dark:text-green-200">
                                        Paid
                                    </span>
                                @elseif($transaction->status === 'pending')
                                    <span class="px-2 py-0.5 rounded border text-xs text-yellow-700 border-yellow-400 bg-yellow-100 dark:bg-yellow-900 dark:border-yellow-700 dark:text-yellow-200">
                                        Pending
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded border text-xs text-red-700 border-red-400 bg-red-100 dark:bg-red-900 dark:border-red-700 dark:text-red-200">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 dark:text-zinc-300">
                                {{ $transaction->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <a
                                    href="{{ route('admin.transactions.show', $transaction) }}"
                                    wire:navigate
                                    class="px-3 py-1.5 rounded border dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 hover:bg-zinc-50 dark:hover:bg-zinc-700"
                                >Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-zinc-500 dark:text-zinc-400">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $transactions->links() }}</div>
    </div>
</div>