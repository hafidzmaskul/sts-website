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
                                        £{{ number_format($item->unit_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-black">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium text-black">
                                        £{{ number_format($item->total_price, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-2 text-right text-black">Subtotal</td>
                                <td class="px-6 py-2 text-right font-medium text-black">
                                    £{{ number_format($transaction->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-6 py-2 text-right text-black">Tax</td>
                                <td class="px-6 py-2 text-right font-medium text-black">
                                    £{{ number_format($transaction->tax_amount, 2) }}</td>
                            </tr>
                            @if($transaction->coupon_id && $transaction->coupon)
                                <tr>
                                    <td colspan="3" class="px-6 py-2 text-right text-black">
                                        Discount <span
                                            class="text-xs text-gray-500">({{ $transaction->coupon->code }})</span>
                                    </td>
                                    <td class="px-6 py-2 text-right font-medium text-green-600">
                                        -£{{ number_format($transaction->discount_amount, 2) }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="px-6 py-2 text-right text-black">Shipping</td>
                                <td class="px-6 py-2 text-right font-medium text-black">
                                    £{{ number_format($transaction->shipping_price, 2) }}</td>
                            </tr>
                            <tr class="text-lg">
                                <td colspan="3" class="px-6 py-4 text-right font-bold text-black">Total</td>
                                <td class="px-6 py-4 text-right font-bold text-indigo-600">
                                    £{{ number_format($transaction->total_amount, 2) }}</td>
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
                            {{ $transaction->customer->phone ?? $transaction->customer->user->phone ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-black uppercase">Checkout Email</p>
                        <p class="text-black">{{ $transaction->contact_email ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row: Shipping & Status -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        <!-- Shipping Info -->
        <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="font-bold text-black">Shipping Details</h2>
            </div>
            <div class="p-4 space-y-4">
                <div>
                    <p class="text-xs font-medium text-black uppercase">Recipient</p>
                    <p class="text-black">{{ $transaction->shipping_first_name }}
                        {{ $transaction->shipping_last_name }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium text-black uppercase">Phone</p>
                    <p class="text-black">{{ $transaction->shipping_phone_number ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-black uppercase">Address</p>
                    <p class="text-black">{{ $transaction->shipping_address }}</p>
                    <p class="text-black">
                        {{ $transaction->shipping_city }}, {{ $transaction->shipping_postal_code }}
                    </p>
                    <p class="text-black">{{ $transaction->shipping_country }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-black uppercase">Shipping Method</p>
                    <p class="text-black">{{ $transaction->shipping_method ?? 'Standard' }}</p>
                </div>
            </div>
        </div>

        <!-- Transaction Status -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50 bg-white">
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

                <!-- Update Status Form -->
                <div class="space-y-4 pt-4 border-t border-gray-100">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide">Update Status</label>
                    <form wire:submit="updateStatus" class="space-y-4">
                        <select wire:model="newStatus"
                            class="block w-full text-sm border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-btn-primary-ring text-black py-2.5 px-3">
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="left the storage">Left the storage</option>
                            <option value="in transit">In transit</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>

                        <textarea wire:model="notes" rows="3"
                            class="block w-full text-sm border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-btn-primary-ring text-black py-2.5 px-3"
                            placeholder="Add a note (optional)..."></textarea>

                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Update Status
        </button>
                    </form>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <div class="mb-4">
                        <p class="text-xs font-medium text-black uppercase">Payment Method</p>
                        <p class="text-black">
                            {{ $transaction->payment_method ?? $transaction->shipping_payment_method ?? 'Not Set' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-black uppercase">Order Date</p>
                        <p class="text-black">{{ $transaction->created_at->format('M d, Y H:i:s') }}</p>
                    </div>
                </div>

                <!-- Status History Timeline -->
                @if($transaction->statusHistory->count() > 0)
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-xs font-medium text-black uppercase mb-3">History</p>
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach($transaction->statusHistory as $history)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                                                    aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span
                                                        class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center ring-8 ring-white">
                                                        <flux:icon.clock class="h-4 w-4 text-indigo-600" />
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-black">
                                                            Changed to <span
                                                                class="font-medium text-gray-900">{{ ucfirst($history->status) }}</span>
                                                        </p>
                                                        @if($history->notes)
                                                            <p class="text-xs text-gray-500 mt-1">{{ $history->notes }}</p>
                                                        @endif
                                                        <p class="text-xs text-gray-400 mt-0.5">by
                                                            {{ $history->user->name ?? 'System' }}
                                                        </p>
                                                    </div>
                                                    <div class="text-right text-xs whitespace-nowrap text-gray-500">
                                                        {{ $history->created_at->format('M d, H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>