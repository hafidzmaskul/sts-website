<div class="p-6 space-y-6 dark:bg-zinc-900">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $product->title }}</h1>
                <span
                    class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300' }}">
                    {{ ucfirst($product->status) }}
                </span>
            </div>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">{{ $product->brand_name }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.products.index') }}"
                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800">
                Back to List
            </a>
            <a href="{{ route('admin.products.edit', $product->id) }}"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Edit Product
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column (Details) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Basic Details Card -->
            <div class="bg-white rounded-xl shadow p-6 dark:bg-zinc-900 dark:border dark:border-zinc-700">
                <h2 class="text-lg font-semibold mb-4 dark:text-white">Basic Information</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">Slug</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-zinc-200">{{ $product->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">Price</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-zinc-200">
                            @if($product->is_sign_up_for_pricing)
                                <span class="italic">Sign up for pricing</span>
                            @else
                                {{ $product->base_price ? '£' . number_format($product->base_price, 2) : 'N/A' }}
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">Exclusivity</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-zinc-200">
                            {{ $product->is_exclusive ? 'Exclusive Product' : 'Standard' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">Categories</dt>
                        <dd class="mt-1 flex flex-wrap gap-2">
                            @forelse($product->categories as $category)
                                <span
                                    class="px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-700 dark:bg-zinc-800 dark:text-zinc-300">
                                    {{ $category->name }}
                                </span>
                            @empty
                                <span class="text-gray-400 italic">No categories</span>
                            @endforelse
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Rich Text Content Sections -->
            <div class="bg-white rounded-xl shadow p-6 dark:bg-zinc-900 dark:border dark:border-zinc-700 space-y-8">
                @if($product->product_overview)
                    <div>
                        <h3 class="text-md font-semibold mb-2 text-gray-900 dark:text-white">Product Overview</h3>
                        <div class="prose max-w-none dark:prose-invert text-sm text-gray-700 dark:text-zinc-300">
                            {!! $product->product_overview !!}
                        </div>
                    </div>
                @endif

                @if($product->key_feature)
                    <div class="border-t pt-6 dark:border-zinc-700">
                        <h3 class="text-md font-semibold mb-2 text-gray-900 dark:text-white">Key Features</h3>
                        <div class="prose max-w-none dark:prose-invert text-sm text-gray-700 dark:text-zinc-300">
                            {!! $product->key_feature !!}
                        </div>
                    </div>
                @endif

                @if($product->main_feature)
                    <div class="border-t pt-6 dark:border-zinc-700">
                        <h3 class="text-md font-semibold mb-2 text-gray-900 dark:text-white">Main Features</h3>
                        <div class="prose max-w-none dark:prose-invert text-sm text-gray-700 dark:text-zinc-300">
                            {!! $product->main_feature !!}
                        </div>
                    </div>
                @endif

                @if($product->information)
                    <div class="border-t pt-6 dark:border-zinc-700">
                        <h3 class="text-md font-semibold mb-2 text-gray-900 dark:text-white">Information</h3>
                        <div class="prose max-w-none dark:prose-invert text-sm text-gray-700 dark:text-zinc-300">
                            {!! $product->information !!}
                        </div>
                    </div>
                @endif
            </div>

            <!-- SEO Card -->
            <div class="bg-white rounded-xl shadow p-6 dark:bg-zinc-900 dark:border dark:border-zinc-700">
                <h2 class="text-lg font-semibold mb-4 dark:text-white">SEO Metadata</h2>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">SEO Title</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-zinc-200">{{ $product->seo_title ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">SEO Description</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-zinc-200">{{ $product->seo_description ?? '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-zinc-400">SEO Keywords</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-zinc-200">{{ $product->seo_keywords ?? '-' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Right Column (Images) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow p-6 dark:bg-zinc-900 dark:border dark:border-zinc-700 sticky top-6">
                <h2 class="text-lg font-semibold mb-4 dark:text-white">Product Images</h2>
                @if($product->images->isNotEmpty())
                    <div class="space-y-4">
                        <!-- Main Image -->
                        <div
                            class="aspect-square w-full bg-gray-100 rounded-lg overflow-hidden border dark:border-zinc-700">
                            <img src="{{ Storage::url($product->images->first()->image_path) }}"
                                class="w-full h-full object-cover">
                        </div>

                        <!-- Thumbnail Grid -->
                        @if($product->images->count() > 1)
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($product->images->skip(1) as $image)
                                    <div class="aspect-square bg-gray-100 rounded overflow-hidden border dark:border-zinc-700">
                                        <img src="{{ Storage::url($image->image_path) }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 rounded-lg dark:bg-zinc-800">
                        <p class="text-gray-500 text-sm dark:text-zinc-400">No images available.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>