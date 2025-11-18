<div>
    <header class="sticky top-0 z-10 border-b border-zinc-200 bg-white/50 backdrop-blur dark:border-zinc-700 dark:bg-zinc-800/50">
        <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <h1 class="text-xl font-semibold dark:text-white">
                {{ $product->name }}
            </h1>
            <div class="flex items-center gap-2">
                <a 
                    href="{{ route('admin.products.index') }}" 
                    wire:navigate
                    class="px-4 py-2 rounded-lg border dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"
                >
                    Back to List
                </a>
                @can('products.edit')
                <a 
                    href="{{ route('admin.products.edit', $product) }}" 
                    wire:navigate
                    class="px-4 py-2 rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 transition-colors"
                >
                    Edit Product
                </a>
                @endcan
            </div>
        </div>
    </header>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="md:col-span-2 space-y-6">
                @if($product->image)
                    <div class="rounded-2xl bg-white p-6 dark:bg-zinc-900 border dark:border-zinc-700">
                        <label class="block text-sm font-medium mb-2 dark:text-zinc-100">Product Image</label>
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full rounded-lg object-cover">
                    </div>
                @endif

                <div class="rounded-2xl bg-white p-6 dark:bg-zinc-900 border dark:border-zinc-700">
                    <h3 class="text-lg font-medium mb-2 dark:text-white">Content</h3>
                    <div class="text-zinc-700 dark:text-zinc-300 whitespace-pre-wrap">
                        {{ $product->content }}
                    </div>
                </div>
            </div>

            <div class="md:col-span-1 space-y-6">
                <div class="rounded-2xl bg-white p-6 dark:bg-zinc-900 border dark:border-zinc-700 space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Price</label>
                        <p class="text-2xl font-bold dark:text-white">£{{ number_format($product->price, 2) }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Status</label>
                        @if($product->status)
                            <span class="px-2 py-0.5 rounded border text-xs text-green-700 border-green-400 bg-green-100 dark:bg-green-900 dark:border-green-700 dark:text-green-200">
                                Published
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded border text-xs text-zinc-600 border-zinc-400 bg-zinc-100 dark:bg-zinc-700 dark:border-zinc-600 dark:text-zinc-300">
                                Draft
                            </span>
                        @endif
                    </div>

                    @if($product->attachment)
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-zinc-100">Attachment</label>
                        <a href="{{ Storage::url($product->attachment) }}" target="_blank" class="text-sm text-blue-500 hover:text-blue-400">
                            Download Attachment
                        </a>
                    </div>
                    @endif
                </div>

                <div class="rounded-2xl bg-white p-6 dark:bg-zinc-900 border dark:border-zinc-700 space-y-4">
                    <label class="block text-sm font-medium dark:text-zinc-100">Related Services</label>
                    <div class="flex flex-wrap gap-2">
                        @forelse($product->services as $service)
                            <span class="px-2 py-0.5 rounded border text-xs dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100">
                                {{ $service->name }}
                            </span>
                        @empty
                            <p class="text-sm dark:text-zinc-400">No related services.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-2xl bg-white p-6 dark:bg-zinc-900 border dark:border-zinc-700 space-y-4 text-sm">
                        <div>
                        <label class="block font-medium dark:text-zinc-100">Author</label>
                        <p class="dark:text-zinc-300">{{ $product->user->name }}</p>
                    </div>
                        <div>
                        <label class="block font-medium dark:text-zinc-100">Created At</label>
                        <p class="dark:text-zinc-300">{{ $product->created_at->format('M d, Y - H:i') }}</p>
                    </div>
                        <div>
                        <label class="block font-medium dark:text-zinc-100">Last Updated</label>
                        <p class="dark:text-zinc-300">{{ $product->updated_at->format('M d, Y - H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>