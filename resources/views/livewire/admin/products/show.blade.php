<div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900">{{ $product->title }}</h1>
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    <span
                        class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $product->status === 'active' ? 'bg-green-600' : 'bg-gray-500' }}"></span>
                    {{ ucfirst($product->status) }}
                </span>
            </div>
            <p class="text-sm text-gray-500 mt-1">{{ $product->brand->name ?? '-' }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.products.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Back to List
            </a>
            <a href="{{ route('admin.products.edit', $product->id) }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Product
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column (Details) -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Basic Details Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Basic Information</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Slug</dt>
                            <dd class="mt-1 text-sm text-gray-900 flex items-center">
                                <span
                                    class="bg-gray-100 rounded px-2 py-0.5 font-mono text-xs">{{ $product->slug }}</span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Price (GBP)</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">
                                {{ $product->base_price ? '£' . number_format($product->base_price, 2) : 'N/A' }}
                                @if($product->is_sign_up_for_pricing)
                                    <span
                                        class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        Sign up for pricing
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Pricing Formula</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($product->pricingFormula)
                                    <div class="font-medium">{{ $product->pricingFormula->label }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $product->pricingFormula->type->label() }}
                                        {{ $product->pricingFormula->value }}
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">None</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Exclusivity</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($product->is_exclusive)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        Exclusive Product
                                    </span>
                                @else
                                    <span class="text-gray-500">Standard</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Categories</dt>
                            <dd class="mt-1 flex flex-wrap gap-2">
                                @forelse($product->categories as $category)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                        @if($category->parent)
                                            <span class="text-gray-500 mr-1">{{ $category->parent->name }} ›</span>
                                        @endif
                                        {{ $category->name }}
                                    </span>
                                @empty
                                    <span class="text-gray-400 italic text-xs">No categories</span>
                                @endforelse
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Rich Text Content Sections -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="divide-y divide-gray-200">
                    @if($product->product_overview)
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Overview</h3>
                            <div class="prose prose-sm max-w-none text-gray-700">
                                {!! $product->product_overview !!}
                            </div>
                        </div>
                    @endif

                    @if($product->key_feature)
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Key Features</h3>
                            <div class="prose prose-sm max-w-none text-gray-700">
                                {!! $product->key_feature !!}
                            </div>
                        </div>
                    @endif

                    @if($product->main_feature)
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Main Features</h3>
                            <div class="prose prose-sm max-w-none text-gray-700">
                                {!! $product->main_feature !!}
                            </div>
                        </div>
                    @endif

                    @if($product->specification)
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Specification</h3>
                            <div class="prose prose-sm max-w-none text-gray-700">
                                {!! $product->specification !!}
                            </div>
                        </div>
                    @endif

                    @if($product->information)
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h3>
                            <div class="prose prose-sm max-w-none text-gray-700">
                                {!! $product->information !!}
                            </div>
                        </div>
                    @endif

                    @if(!$product->product_overview && !$product->key_feature && !$product->main_feature && !$product->specification && !$product->information)
                        <div class="p-8 text-center text-gray-500">
                            No detailed content available.
                        </div>
                    @endif
                </div>
            </div>

            <!-- SEO Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">SEO Metadata</h2>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">SEO Title</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $product->seo_title ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">SEO Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $product->seo_description ?? '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">SEO Keywords</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if(!empty($product->seo_keywords))
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(explode(',', $product->seo_keywords) as $keyword)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ trim($keyword) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    -
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Right Column (Images) -->
        <div class="lg:col-span-1 border-l lg:border-l-0 lg:pl-0">
            <!-- The sticky container needs to be relative to the viewport or parent, make sure parent is tall enough -->
            <div class="space-y-6 sticky top-6">
                <!-- Brand Information Card -->
                @if($product->brand)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Brand Information</h2>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-4 mb-4">
                                @if($product->brand->image)
                                    <div
                                        class="flex-shrink-0 h-14 w-14 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden">
                                        <img src="{{ Storage::url($product->brand->image) }}" alt="{{ $product->brand->name }}"
                                            class="h-full w-full object-contain">
                                    </div>
                                @else
                                    <div
                                        class="flex-shrink-0 h-14 w-14 rounded-lg border border-gray-200 bg-gray-100 flex items-center justify-center text-gray-400">
                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="text-base font-medium text-gray-900">{{ $product->brand->name }}</h3>
                                    @if($product->brand->website)
                                        <a href="{{ $product->brand->website }}" target="_blank"
                                            class="text-sm text-indigo-600 hover:text-indigo-500 hover:underline inline-flex items-center">
                                            Visit Website
                                            <svg class="ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                </path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @if($product->brand->description)
                                <div class="text-sm text-gray-600 line-clamp-4">
                                    {{ $product->brand->description }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Product Images</h2>
                    </div>
                    <div class="p-6">
                        @if($product->images->isNotEmpty())
                            <div class="space-y-4">
                                <!-- Main Image (First one) -->
                                <div
                                    class="aspect-square w-full rounded-lg overflow-hidden border border-gray-200 bg-gray-50 group relative">
                                    <img src="{{ Storage::url($product->images->first()->image_path) }}"
                                        class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                                </div>

                                <!-- Grid for remaining -->
                                @if($product->images->count() > 1)
                                    <div class="grid grid-cols-4 gap-2">
                                        @foreach($product->images->skip(1) as $image)
                                            <div
                                                class="aspect-square rounded-md overflow-hidden border border-gray-200 bg-gray-50 cursor-pointer hover:ring-2 hover:ring-indigo-500 transition-all">
                                                <img src="{{ Storage::url($image->image_path) }}"
                                                    class="w-full h-full object-cover">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No images available.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>