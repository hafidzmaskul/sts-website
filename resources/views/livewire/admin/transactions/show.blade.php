<div class="p-6 space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.transactions.index') }}"
            class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition">
            <flux:icon.chevron-left class="w-5 h-5" />
        </a>
        <h1 class="text-2xl font-bold text-black">Transaction: {{ $transaction->invoice_code }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- left column: Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h2 class="font-bold text-black">Items</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-left">
                            <tr>
                                <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-black">Product
                                </th>
                                <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-black">Price
                                </th>
                                <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-black">Qty
                                </th>
                                <th
                                    class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-black text-right">
                                    Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($transaction->items as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-black">{{ $item->product_name_snapshot }}</div>
                                        @if($item->product)
                                            <div class="text-xs text-black">SKU: {{ $item->product->slug }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-black">
                                        ${{ number_format($item->unit_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-black">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium text-black">
                                        ${{ number_format($item->total_price, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-2 text-right text-black">Subtotal</td>
                                <td class="px-6 py-2 text-right font-medium text-black">
                                    ${{ number_format($transaction->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-6 py-2 text-right text-black">Tax</td>
                                <td class="px-6 py-2 text-right font-medium text-black">
                                    ${{ number_format($transaction->tax_amount, 2) }}</td>
                            </tr>
                            <tr class="text-lg">
                                <td colspan="3" class="px-6 py-4 text-right font-bold text-black">Total</td>
                                <td class="px-6 py-4 text-right font-bold text-indigo-600">
                                    ${{ number_format($transaction->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Info -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-bold text-black">Customer Info</h2>
                </div>
                <div class="p-4 space-y-4">
                    <div>
                        <p class="text-xs font-medium text-black uppercase">Name</p>
                        <p class="text-black">{{ $transaction->customer->user->name ?? 'Guest' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-black uppercase">Email</p>
                        <p class="text-black">{{ $transaction->customer->user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-black uppercase">Phone</p>
                        <p class="text-black">
                            {{ $transaction->customer->phone ?? $transaction->customer->user->phone ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Transaction Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-bold text-black">Transaction Status</h2>
                </div>
                <div class="p-4 space-y-4">
                    <div>
                        <p class="text-xs font-medium text-black uppercase">Status</p>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($transaction->status === 'completed') bg-green-100 text-green-800
                                @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($transaction->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-black @endif">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-black uppercase">Payment Method</p>
                        <p class="text-black">{{ $transaction->payment_method ?? 'Not Set' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-black uppercase">Order Date</p>
                        <p class="text-black">{{ $transaction->created_at->format('M d, Y H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
