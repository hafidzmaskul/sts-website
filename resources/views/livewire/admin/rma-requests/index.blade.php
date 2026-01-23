<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">{{ __('RMA Requests') }}</h2>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <!-- Card Header: Search & Filter -->
            <div
                class="px-4 py-4 border-b bg-gray-50 rounded-t-xl flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="max-w-xl w-full">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="7" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-3.5-3.5" />
                            </svg>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            class="block w-full md:w-96 pl-10 pr-3 py-2 rounded-lg border border-gray-300 bg-white text-black placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            placeholder="Search order, product, or customer...">
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                    <div class="w-full md:w-48">
                        <select wire:model.live="returnType"
                            class="block w-full rounded-lg border border-gray-300 bg-white text-black py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="">All Types</option>
                            <option value="exchange">Exchange</option>
                            <option value="refund">Refund</option>
                            <option value="repair">Repair</option>
                            <option value="store_credit">Store Credit</option>
                        </select>
                    </div>
                    <div class="w-full md:w-48">
                        <select wire:model.live="status"
                            class="block w-full rounded-lg border border-gray-300 bg-white text-black py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                                {{ __('Date') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                                {{ __('Order #') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                                {{ __('Product') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                                {{ __('Customer') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                                {{ __('Type') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                                {{ __('Reason') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                                {{ __('Status') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($rmaRequests as $request)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                    {{ $request->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                    {{ $request->order_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                    {{ $request->product_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                    @if($request->user)
                                        {{ $request->user->name }}
                                    @else
                                        <span class="text-gray-500">Guest</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($request->return_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-black max-w-xs truncate"
                                    title="{{ $request->return_reason }}">
                                    {{ $request->return_reason }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($request->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                    @if($request->proof_of_purchase_path)
                                        <a href="{{ Storage::url($request->proof_of_purchase_path) }}" target="_blank"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            {{ __('View Proof') }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($rmaRequests->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $rmaRequests->links() }}
                </div>
            @endif
        </div>
    </div>
</div>