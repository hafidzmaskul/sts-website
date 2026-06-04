<div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6" style="color: black;">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold" style="color: black;">{{ $product->title }}</h1>
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->status === 'active' ? 'bg-green-100' : 'bg-gray-100' }} {{ $product->status === 'active' ? 'text-green-800' : 'text-gray-800' }}"
                    style="color: black;">
                    <span
                        class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $product->status === 'active' ? 'bg-green-600' : 'bg-gray-500' }}"></span>
                    {{ ucfirst($product->status) }}
                </span>
            </div>
            <p class="text-sm mt-1" style="color: #555;">{{ $product->brand->name ?? '-' }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.products.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors"
                style="color: black;">
                Back to List
            </a>
            <a href="{{ route('admin.products.edit', $product->id) }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-btn-primary hover:bg-btn-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-btn-primary-ring transition-colors">
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
                    <h2 class="text-lg font-semibold" style="color: black;">Basic Information</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium" style="color: #555;">Slug</dt>
                            <dd class="mt-1 text-sm flex items-center" style="color: black;">
                                <span class="bg-gray-100 rounded px-2 py-0.5 font-mono text-xs"
                                    style="color: black;">{{ $product->slug }}</span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium" style="color: #555;">Price (GBP)</dt>
                            <dd class="mt-1 text-sm font-semibold" style="color: black;">
                                {{ $product->base_price ? '£' . number_format($product->base_price, 2) : 'N/A' }}
                                @if($product->is_sign_up_for_pricing)
                                    <span
                                        class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100"
                                        style="color: #1e40af;">
                                        Sign up for pricing
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium" style="color: #555;">Special Price (GBP)</dt>
                            <dd class="mt-1 text-sm font-semibold" style="color: black;">
                                {{ $product->special_price ? '£' . number_format($product->special_price, 2) : 'N/A' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium" style="color: #555;">SKU</dt>
                            <dd class="mt-1 text-sm flex items-center" style="color: black;">
                                {{ $product->sku ?? '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium" style="color: #555;">Exclusivity</dt>
                            <dd class="mt-1 text-sm" style="color: black;">
                                @if($product->is_exclusive)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100"
                                        style="color: #6d28d9;">
                                        Exclusive Product
                                    </span>
                                @else
                                    <span style="color: #555;">Standard</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium" style="color: #555;">Categories</dt>
                            <dd class="mt-1 flex flex-wrap gap-2">
                                @forelse($product->categories as $category)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 border border-gray-200"
                                        style="color: black;">
                                        @if($category->parent)
                                            <span class="mr-1" style="color: #555;">{{ $category->parent->name }} ›</span>
                                        @endif
                                        {{ $category->name }}
                                    </span>
                                @empty
                                    <span class="italic text-xs" style="color: #aaa;">No categories</span>
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
                            <h3 class="text-lg font-semibold mb-4" style="color: black;">Product Overview</h3>
                            <div class="prose prose-sm max-w-none" style="color: black;">
                                {!! $product->product_overview !!}
                            </div>
                        </div>
                    @endif

                    @if($product->key_feature)
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4" style="color: black;">Key Features</h3>
                            <div class="prose prose-sm max-w-none" style="color: black;">
                                {!! $product->key_feature !!}
                            </div>
                        </div>
                    @endif

                    @if($product->main_feature)
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4" style="color: black;">Main Features</h3>
                            <div class="prose prose-sm max-w-none" style="color: black;">
                                {!! $product->main_feature !!}
                            </div>
                        </div>
                    @endif

                    @if($product->specification)
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4" style="color: black;">Specification</h3>
                            <div class="prose prose-sm max-w-none" style="color: black;">
                                {!! $product->specification !!}
                            </div>
                        </div>
                    @endif

                    @if($product->information)
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4" style="color: black;">Additional Information</h3>
                            <div class="prose prose-sm max-w-none" style="color: black;">
                                {!! $product->information !!}
                            </div>
                        </div>
                    @endif

                    @if(!$product->product_overview && !$product->key_feature && !$product->main_feature && !$product->specification && !$product->information)
                        <div class="p-8 text-center" style="color: #555;">
                            No detailed content available.
                        </div>
                    @endif
                </div>
            </div>

            <!-- SEO Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold" style="color: black;">SEO Metadata</h2>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider" style="color: #555;">SEO Title</dt>
                            <dd class="mt-1 text-sm" style="color: black;">{{ $product->seo_title ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider" style="color: #555;">SEO
                                Description</dt>
                            <dd class="mt-1 text-sm" style="color: black;">{{ $product->seo_description ?? '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider" style="color: #555;">SEO Keywords
                            </dt>
                            <dd class="mt-1 text-sm" style="color: black;">
                                @if(!empty($product->seo_keywords))
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(explode(',', $product->seo_keywords) as $keyword)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100"
                                                style="color: black;">
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

            <!-- Advance Pricing Card -->
            @if($product->customerPrices->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold" style="color: black;">Advance Pricing</h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold sm:pl-6"
                                            style="color: black;">
                                            Customer</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold"
                                            style="color: black;">
                                            Price (GBP)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach($product->customerPrices as $customer)
                                        <tr>
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium sm:pl-6"
                                                style="color: black;">
                                                {{ $customer->name }}
                                                <span class="block text-xs font-normal"
                                                    style="color: #555;">{{ $customer->email }}</span>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm" style="color: #555;">
                                                £{{ number_format($customer->pivot->price, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Attachments Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold" style="color: black;">Attachments</h2>
                </div>
                <div class="p-6">
                    @if($product->attachments->isNotEmpty())
                        <div class="space-y-3">
                            @foreach($product->attachments as $attachment)
                                <div
                                    class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition group">
                                    <div class="bg-indigo-50 text-indigo-600 p-2 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-sm text-gray-900 truncate" style="color: black;">
                                            <div class="flex items-center gap-2">
                                                {{ $attachment->name }}
                                                @if($attachment->is_public)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                        Public
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                        Not Public
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="block text-xs font-normal text-gray-500 mt-0.5">
                                                {{ basename($attachment->file_path) }}
                                            </span>
                                        </div>
                                        <a href="{{ Storage::url($attachment->file_path) }}" target="_blank"
                                            class="text-xs text-indigo-600 hover:text-indigo-800 hover:underline">
                                            Download
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                            <svg class="mx-auto h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                style="color: #aaa;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            <p class="mt-2 text-sm" style="color: #555;">No attachments.</p>
                        </div>
                    @endif
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
                            <h2 class="text-lg font-semibold" style="color: black;">Brand Information</h2>
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
                                    <div class="flex-shrink-0 h-14 w-14 rounded-lg border border-gray-200 bg-gray-100 flex items-center justify-center"
                                        style="color: #aaa;">
                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="text-base font-medium" style="color: black;">{{ $product->brand->name }}</h3>
                                    @if($product->brand->website)
                                        <a href="{{ $product->brand->website }}" target="_blank"
                                            class="text-sm hover:underline inline-flex items-center" style="color: #1e40af;">
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
                                <div class="text-sm line-clamp-4" style="color: #555;">
                                    {{ $product->brand->description }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold" style="color: black;">Product Images</h2>
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
                                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    style="color: #aaa;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-2 text-sm" style="color: #555;">No images available.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>