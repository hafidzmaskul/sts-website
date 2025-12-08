<div class="p-6 space-y-6 dark:bg-zinc-900">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Products</h1>
        <a href="{{ route('admin.products.create') }}"
            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            Create Product
        </a>
    </div>

    <div class="flex items-center gap-3">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search products..."
            class="w-full md:w-80 rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
    </div>

    <div class="overflow-x-auto rounded-xl border dark:border-zinc-700 dark:bg-zinc-800">
        <table class="min-w-full text-sm">
            <thead class="bg-zinc-50 text-left dark:bg-zinc-900 dark:text-zinc-200">
                <tr>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider">Brand</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider">Categories</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse($products as $product)
                    <tr class="dark:text-zinc-100">
                        <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $product->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400">{{ $product->brand_name }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 dark:text-zinc-400">
                            <div class="flex flex-wrap gap-1">
                                @foreach($product->categories as $cat)
                                    <span
                                        class="px-2 py-0.5 text-xs bg-gray-100 dark:bg-zinc-700 rounded">{{ $cat->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400">
                            @if($product->is_sign_up_for_pricing)
                                <span class="text-xs italic">Sign Up</span>
                            @else
                                {{ $product->base_price ? '£' . number_format($product->base_price, 2) : '-' }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 py-1 text-xs rounded-full {{ $product->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium space-x-2">
                            <a href="{{ route('admin.products.show', $product->id) }}"
                                class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">View</a>
                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</a>
                            <button wire:confirm="Are you sure you want to delete this product?"
                                wire:click="delete({{ $product->id }})"
                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-zinc-400">No products found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>