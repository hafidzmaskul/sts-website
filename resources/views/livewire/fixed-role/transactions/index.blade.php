<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">My Transactions</h1>
            <p class="mt-2 text-sm text-gray-700">A list of all your transactions.</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-gray-500">Total Transactions</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ number_format($totalTransactions) }}
            </dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-gray-500">Total Spent</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">${{ number_format($totalAmount, 2) }}
            </dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-gray-500">Open Invoices</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-red-600">
                ${{ number_format($openInvoicesAmount, 2) }} <span
                    class="text-sm text-gray-500 font-normal">({{ $openInvoicesCount }})</span></dd>
        </div>
    </dl>

    <!-- Filters -->
    <div class="mt-8 bg-white p-4 shadow rounded-lg">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-4 items-end">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model.live="status" id="status"
                    class="block w-full rounded-lg border-gray-300 py-2 pl-3 pr-10 text-base focus:border-[#0079C2] focus:outline-none focus:ring-[#0079C2] sm:text-sm">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="processed">Processed</option>
                    <option value="shipped">Shipped</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <label for="date_start" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" wire:model.live="dateStart" id="date_start"
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0079C2] focus:ring-[#0079C2] sm:text-sm px-3 py-2">
            </div>
            <div>
                <label for="date_end" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" wire:model.live="dateEnd" id="date_end"
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#0079C2] focus:ring-[#0079C2] sm:text-sm px-3 py-2">
            </div>
            <div>
                <button wire:click="$set('status', ''); $set('dateStart', ''); $set('dateEnd', '');"
                    class="inline-flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#0079C2] focus:ring-offset-2">
                    Clear Filters
                </button>
            </div>
        </div>
    </div>
    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                    Invoice</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Date
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Amount
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Created By
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">View</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-[#0079C2] sm:pl-6">
                                        {{ $transaction->invoice_code }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ $transaction->created_at->format('M j, Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        ${{ number_format($transaction->total_amount, 2) }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ $transaction->customer->user->name ?? 'N/A' }}
                                    </td>
                                    <td
                                        class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <a href="{{ route('dashboard.transactions.show', $transaction) }}"
                                            class="text-[#0079C2] hover:text-[#00619e]">View<span class="sr-only">,
                                                {{ $transaction->invoice_code }}</span></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 pl-4 pr-3 text-sm text-center text-gray-500 sm:pl-6">
                                        No transactions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4">
        {{ $transactions instanceof \Illuminate\Pagination\LengthAwarePaginator ? $transactions->links() : '' }}
    </div>
</div>