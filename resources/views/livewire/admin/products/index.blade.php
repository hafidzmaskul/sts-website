<div class="p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-black">Products</h1>
        <a href="{{ route('admin.products.create') }}"
            class="w-full md:w-auto bg-[#0079C2] text-white px-4 py-2 rounded-lg hover:cursor-pointer transition">
            Create Product
        </a>
    </div>

    <div class="flex items-center gap-3">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search products..."
            style="color: #000; border: 1px solid #AEAEAE; background-color: white; placeholder-color: #D2D2D2 !important;"
            class="w-full md:w-80 rounded-lg px-3 py-2 placeholder-[#D2D2D2] border-[#AEAEAE] placeholder:text-[#D2D2D2]"
        >
    </div>

    <div class="overflow-x-auto rounded-xl border">
        <table class="min-w-full text-sm">
            <thead style="background-color: transparent;">
                <tr>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color: #000;">Product</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color: #000;">Brand</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color: #000;">Categories</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color: #000;">Price</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color: #000;">Status</th>
                    <th class="px-6 py-3 font-medium uppercase tracking-wider" style="color: #000;">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($products as $product)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-black">{{ $product->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-black">{{ $product->brand_name }}</td>
                        <td class="px-6 py-4 text-black">
                            <div class="flex flex-wrap gap-1">
                                @foreach($product->categories as $cat)
                                    <span
                                        class="px-2 py-0.5 text-xs border rounded border-[#AEAEAE] text-[#AEAEAE] bg-transparent">{{ $cat->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-black">
                            @if($product->is_sign_up_for_pricing)
                                <span class="text-xs italic text-black">Sign Up</span>
                            @else
                                <span class="text-black">{{ $product->base_price ? '£' . number_format($product->base_price, 2) : '-' }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 py-1 text-xs rounded-full border {{ $product->status === 'active' ? 'border-green-600 text-green-600' : 'border-[#AEAEAE] text-[#AEAEAE]' }} bg-transparent">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium space-x-2 flex items-center">
                            <a href="{{ route('admin.products.show', $product->id) }}"
                                class="border border-black text-black px-2 py-1 rounded-lg bg-white hover:bg-black hover:text-white transition">
                                View
                            </a>
                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                class="border border-black text-black px-2 py-1 rounded-lg bg-white hover:bg-black hover:text-white transition flex items-center" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 1200 1200" fill="none" style="color: #000000;">
                                    <path fill="currentColor" d="M0 0v1200h1200V424.292l-196.875 196.875v381.958h-806.25v-806.25h381.958L775.708 0zm1050 0l-76.831 76.831l150 150L1200 150zM936.914 113.086L497.168 552.832l150 150l439.746-439.746zM441.943 622.339c-2.225.034-4.493.195-6.738.366v142.09h142.09c0-38.708-18.492-78.039-47.314-105.542c-23.842-22.751-54.675-37.428-88.038-36.914"></path>
                                </svg>
                            </a>
                            <button wire:confirm="Are you sure you want to delete this product?"
                                wire:click="delete({{ $product->id }})"
                                class="border border-black text-black px-2 py-1 rounded-lg bg-white hover:bg-black hover:text-white transition flex items-center" title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 12 12" fill="none" style="color: #000000;">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" d="M2 2.5h8" stroke-width="1"></path>
                                    <path fill="currentColor" d="M2 4v7c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4zm3 5.5c0 .28-.22.5-.5.5S4 9.78 4 9.5V6c0-.28.22-.5.5-.5s.5.22.5.5zm3 0c0 .28-.22.5-.5.5S7 9.78 7 9.5V6c0-.28.22-.5.5-.5s.5.22.5.5zM8 3H4V1c0-.55.45-1 1-1h2c.55 0 1 .45 1 1z"></path>
                                </svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-black">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
