<div class="p-6 space-y-6 bg-white">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Manual Product Requests</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-btn-primary-ring sm:text-sm px-3 py-2 border"
                placeholder="Name, Email, Phone">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Product</label>
            <select wire:model.live="productId"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-btn-primary-ring sm:text-sm px-3 py-2 border">
                <option value="">All Products</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->title }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
            <input wire:model.live="dateStart" type="date"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-btn-primary-ring sm:text-sm px-3 py-2 border">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
            <input wire:model.live="dateEnd" type="date"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-btn-primary-ring sm:text-sm px-3 py-2 border">
        </div>
    </div>


    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-zinc-50 text-left">
                    <tr>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">No</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">Date</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">Name</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">Email</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">Phone</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">Product</th>
                        <th class="px-6 py-3 font-medium uppercase tracking-wider text-xs text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($productRequests as $index => $request)
                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out"
                            wire:key="request-{{ $request->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $productRequests->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $request->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-black">
                                {{ $request->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $request->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $request->phone }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($request->product)
                                    <a href="{{ route('admin.products.show', $request->product) }}" class="text-black hover:text-indigo-600 transition-colors inline-flex" title="View details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-2">
                                <a href="{{ route('admin.product-requests.show', $request) }}" class="text-black hover:text-indigo-600 transition-colors inline-flex" title="View details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No requests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $productRequests->links() }}
        </div>
    </div>
</div>