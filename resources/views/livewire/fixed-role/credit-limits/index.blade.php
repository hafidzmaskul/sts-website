<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Credit Limit History</h1>
            <p class="mt-2 text-sm text-gray-700">A detailed history of your company's credit limit transactions.</p>
        </div>
    </div>

    <!-- Balance Card -->
    <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-gray-500">Current Balance</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-[#0079C2]">
                ${{ number_format($currentBalance, 2) }}</dd>
        </div>
    </dl>

    <!-- History Table -->
    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Date
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Description</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Debit
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Credit
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Balance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($creditLimits as $limit)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-500 sm:pl-6">
                                        {{ $limit->created_at->format('M j, Y H:i') }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ $limit->description }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-red-600">
                                        {{ $limit->debit > 0 ? '$' . number_format($limit->debit, 2) : '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-green-600">
                                        {{ $limit->credit > 0 ? '$' . number_format($limit->credit, 2) : '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-gray-900">
                                        ${{ number_format($limit->balance, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 pl-4 pr-3 text-sm text-center text-gray-500 sm:pl-6">
                                        No credit limit history found.
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
        {{ $creditLimits->links() }}
    </div>
</div>