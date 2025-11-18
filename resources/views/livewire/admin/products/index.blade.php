<div>
    <header class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur dark:border-zinc-700 dark:bg-zinc-800/50">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold dark:text-white">
                Products
            </h1>
            @can('products.create')
            <a 
                href="{{ route('admin.products.create') }}" 
                wire:navigate
                class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 transition-colors"
            >
                New Product
            </a>
            @endcan
        </div>
    </header>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-12 sm:px-6 lg:px-8">
        
        <div class="flex items-center gap-3">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Search by name..."
                class="w-full md:w-80 rounded-lg border px-3 py-2 dark:bg-zinc-800 dark:text-white dark:border-zinc-700"
            />
        </div>

        <div class="overflow-x-auto rounded-xl border dark:border-zinc-700 dark:bg-zinc-800">
            <table class="min-w-full text-sm">
                <thead class="bg-zinc-50 text-left dark:bg-zinc-900 dark:text-zinc-200">
                    <tr>
                        <th class="px-4 py-3">Image</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Price</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Related Services</th>
                        <th class="px-4 py-3 w-40">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr class="border-t dark:border-zinc-700">
                            <td class="px-4 py-3">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="h-10 w-16 rounded object-cover">
                                @else
                                    <span class="flex h-10 w-16 items-center justify-center rounded bg-zinc-200 dark:bg-zinc-700">
                                        ?
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium dark:text-white">
                                <a href="{{ route('admin.products.show', $product) }}" wire:navigate class="hover:text-blue-400">
                                    {{ $product->name }}
                                </a>
                                </td>
                            <td class="px-4 py-3 dark:text-zinc-300">£{{ number_format($product->price, 2, '.', ',') }}</td>
                            <td class="px-4 py-3">
                                @if($product->status)
                                    <span class="px-2 py-0.5 rounded border text-xs text-green-700 border-green-400 bg-green-100 dark:bg-green-900 dark:border-green-700 dark:text-green-200">
                                        Published
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded border text-xs text-zinc-600 border-zinc-400 bg-zinc-100 dark:bg-zinc-700 dark:border-zinc-600 dark:text-zinc-300">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 dark:text-zinc-300 text-xs">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($product->services as $service)
                                        <span class="px-2 py-0.5 rounded border dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100">{{ $service->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    @can('products.edit')
                                    <a
                                        href="{{ route('admin.products.edit', $product) }}"
                                        wire:navigate
                                        class="px-3 py-1.5 rounded border dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                                    >Edit</a>
                                    @endcan
                                    @can('products.delete')
                                    <button
                                        wire:click="delete({{ $product->id }})"
                                        wire:confirm="Are you sure you want to delete this product?"
                                        class="px-3 py-1.5 rounded border text-red-600 dark:border-zinc-600 dark:bg-zinc-800 dark:text-red-400"
                                    >Delete</button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-zinc-500 dark:text-zinc-400">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $products->links() }}</div>

    </div>
</div>