<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold" style="color: #000;">{{ $product->title }}</h1>
                <span
                    class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->status === 'active' ? 'border border-green-800 text-green-800 bg-white' : 'border border-gray-800 text-gray-800 bg-white' }}">
                    {{ ucfirst($product->status) }}
                </span>
            </div>
            <p class="text-sm text-gray-500 mt-1">{{ $product->brand_name }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.products.index') }}"
                class="px-4 py-2 bg-white border-2 border-[#0079C2] text-[#0079C2] rounded-lg hover:cursor-pointer hover:bg-[#0079C2] hover:text-white transition">
                Back to List
            </a>
            <a href="{{ route('admin.products.edit', $product->id) }}"
                class="flex items-center justify-center px-4 py-2 bg-white border-2 border-black text-black rounded-lg hover:cursor-pointer transition" style="height: 40px; width: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 1200 1200" style="color:#000000;">
                    <path fill="currentColor" d="M0 0v1200h1200V424.292l-196.875 196.875v381.958h-806.25v-806.25h381.958L775.708 0zm1050 0l-76.831 76.831l150 150L1200 150zM936.914 113.086L497.168 552.832l150 150l439.746-439.746zM441.943 622.339c-2.225.034-4.493.195-6.738.366v142.09h142.09c0-38.708-18.492-78.039-47.314-105.542c-23.842-22.751-54.675-37.428-88.038-36.914"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column (Details) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Basic Details Card -->
            <div class="rounded-xl shadow p-6 border border-gray-200">
                <h2 class="text-lg font-semibold mb-4" style="color: #000;">Basic Information</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium" style="color:#AEAEAE;">Slug</dt>
                        <dd class="mt-1 text-sm text-black">{{ $product->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium" style="color:#AEAEAE;">Price</dt>
                        <dd class="mt-1 text-sm text-black">
                            @if($product->is_sign_up_for_pricing)
                                <span class="italic">Sign up for pricing</span>
                            @else
                                {{ $product->base_price ? '£' . number_format($product->base_price, 2) : 'N/A' }}
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium" style="color:#AEAEAE;">Exclusivity</dt>
                        <dd class="mt-1 text-sm text-black">
                            {{ $product->is_exclusive ? 'Exclusive Product' : 'Standard' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium" style="color:#AEAEAE;">Categories</dt>
                        <dd class="mt-1 flex flex-wrap gap-2">
                            @forelse($product->categories as $category)
                                <span
                                    class="px-2 py-0.5 rounded text-xs border border-gray-300 text-black bg-white">
                                    @if($category->parent)
                                        <span style="color:#AEAEAE;">{{ $category->parent->name }} ></span>
                                    @endif
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
            <div class="rounded-xl shadow p-6 border border-gray-200 space-y-8">
                @if($product->product_overview)
                    <div>
                        <h3 class="text-md font-semibold mb-2" style="color: #000;">Product Overview</h3>
                        <div class="prose max-w-none text-sm text-black">
                            {!! $product->product_overview !!}
                        </div>
                    </div>
                @endif

                @if($product->key_feature)
                    <div class="border-t pt-6 border-gray-200">
                        <h3 class="text-md font-semibold mb-2" style="color: #000;">Key Features</h3>
                        <div class="prose max-w-none text-sm text-black">
                            {!! $product->key_feature !!}
                        </div>
                    </div>
                @endif

                @if($product->main_feature)
                    <div class="border-t pt-6 border-gray-200">
                        <h3 class="text-md font-semibold mb-2" style="color: #000;">Main Features</h3>
                        <div class="prose max-w-none text-sm text-black">
                            {!! $product->main_feature !!}
                        </div>
                    </div>
                @endif

                @if($product->information)
                    <div class="border-t pt-6 border-gray-200">
                        <h3 class="text-md font-semibold mb-2" style="color: #000;">Information</h3>
                        <div class="prose max-w-none text-sm text-black">
                            {!! $product->information !!}
                        </div>
                    </div>
                @endif
            </div>

            <!-- SEO Card -->
            <div class="rounded-xl shadow p-6 border border-gray-200">
                <h2 class="text-lg font-semibold mb-4" style="color: #000;">SEO Metadata</h2>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium" style="color:#AEAEAE;">SEO Title</dt>
                        <dd class="mt-1 text-sm text-black">{{ $product->seo_title ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium" style="color:#AEAEAE;">SEO Description</dt>
                        <dd class="mt-1 text-sm text-black">{{ $product->seo_description ?? '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium" style="color:#AEAEAE;">SEO Keywords</dt>
                        <dd class="mt-1 text-sm text-black">{{ $product->seo_keywords ?? '-' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Right Column (Images) -->
        <div class="lg:col-span-1">
            <div class="rounded-xl shadow p-6 border border-gray-200 sticky top-6">
                <h2 class="text-lg font-semibold mb-4" style="color: #000;">Product Images</h2>
                @if($product->images->isNotEmpty())
                    <div class="space-y-4">
                        <!-- Main Image -->
                        <div
                            class="aspect-square w-full rounded-lg overflow-hidden border border-gray-200 bg-gray-100">
                            <img src="{{ Storage::url($product->images->first()->image_path) }}"
                                class="w-full h-full object-cover">
                        </div>

                        <!-- Thumbnail Grid -->
                        @if($product->images->count() > 1)
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($product->images->skip(1) as $image)
                                    <div class="aspect-square rounded overflow-hidden border border-gray-200 bg-gray-100">
                                        <img src="{{ Storage::url($image->image_path) }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8 rounded-lg bg-[#F8F8F8]">
                        <p class="text-gray-500 text-sm">No images available.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
