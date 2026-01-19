<div class="p-6 space-y-6 bg-white">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Manual Product Requests</h1>
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
                                    <a href="{{ route('admin.products.show', $request->product) }}"
                                        class="text-indigo-600 hover:text-indigo-900 hover:underline">
                                        {{ $request->product->title }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-2">
                                <a href="{{ route('admin.product-requests.show', $request) }}"
                                    class="text-indigo-600 hover:text-indigo-900">
                                    Detail
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