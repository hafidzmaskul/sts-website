<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.quote-builders.index') }}" wire:navigate
                class="flex items-center text-black hover:text-black">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-arrow-left">
                    <path d="m12 19-7-7 7-7" />
                    <path d="M19 12H5" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-black">{{ $quoteBuilder->name }}</h1>
                <p class="text-sm text-gray-500 mt-1">Draft Quote Builder details</p>
            </div>
        </div>
        <div class="flex gap-3">
            @if($quoteBuilder->user)
                <button wire:click="$set('showLoginModal', true)"
                    class="flex items-center px-4 py-2 rounded-lg border border-black bg-white hover:bg-gray-100 text-black shadow-sm transition-colors"
                    style="height: 38px;" title="Login as User">
                    <flux:icon.arrow-right-start-on-rectangle class="w-4 h-4" />
                    <span class="ml-2 text-sm">Login as User</span>
                </button>
            @endif
            <a href="{{ route('admin.quote-builders.index') }}" wire:navigate
                class="px-4 py-2 border border-gray-700 rounded-lg text-black bg-white hover:bg-gray-50 flex items-center justify-center shadow-sm text-sm font-medium transition-colors">
                Back to List
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Customer & Company Info (1/3 width) -->
        <div class="space-y-6 lg:col-span-1">
            <!-- Customer Card -->
            <div class="rounded-xl shadow-sm p-6 border border-gray-200 bg-white">
                <h2 class="text-lg font-semibold mb-4 text-black flex items-center gap-2">
                    <flux:icon.user class="w-5 h-5 text-gray-400" />
                    Customer Details
                </h2>
                @if($quoteBuilder->user)
                    <dl class="space-y-3">
                        <div class="grid grid-cols-3 gap-2 pb-2 border-b border-gray-50 last:border-0 last:pb-0">
                            <dt class="text-sm font-medium text-gray-500 col-span-1">Name</dt>
                            <dd class="text-sm text-black font-semibold col-span-2">
                                <a href="{{ route('admin.customers.show', $quoteBuilder->user->customer?->id ?? '#') }}" class="hover:underline text-indigo-600">
                                    {{ $quoteBuilder->user->name }}
                                </a>
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-2 pb-2 border-b border-gray-50 last:border-0 last:pb-0">
                            <dt class="text-sm font-medium text-gray-500 col-span-1">Email</dt>
                            <dd class="text-sm text-black col-span-2 select-all">{{ $quoteBuilder->user->email }}</dd>
                        </div>
                        <div class="grid grid-cols-3 gap-2 pb-2 border-b border-gray-50 last:border-0 last:pb-0">
                            <dt class="text-sm font-medium text-gray-500 col-span-1">Role</dt>
                            <dd class="text-sm text-black col-span-2">
                                {{ ucfirst($quoteBuilder->user->customer?->role_applied ?? 'customer') }}
                            </dd>
                        </div>
                    </dl>
                @else
                    <p class="text-sm text-gray-500 italic">No user linked to this draft.</p>
                @endif
            </div>

            <!-- Company Card -->
            <div class="rounded-xl shadow-sm p-6 border border-gray-200 bg-white">
                <h2 class="text-lg font-semibold mb-4 text-black flex items-center gap-2">
                    <flux:icon.building-office class="w-5 h-5 text-gray-400" />
                    Company Details
                </h2>
                @if($quoteBuilder->user && $quoteBuilder->user->customer?->company)
                    @php
                        $company = $quoteBuilder->user->customer->company;
                    @endphp
                    <dl class="space-y-3">
                        <div class="grid grid-cols-3 gap-2 pb-2 border-b border-gray-50 last:border-0 last:pb-0">
                            <dt class="text-sm font-medium text-gray-500 col-span-1">Company</dt>
                            <dd class="text-sm text-black font-semibold col-span-2">
                                <a href="{{ route('admin.companies.show', $company->id) }}" class="hover:underline text-indigo-600">
                                    {{ $company->name }}
                                </a>
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-2 pb-2 border-b border-gray-50 last:border-0 last:pb-0">
                            <dt class="text-sm font-medium text-gray-500 col-span-1">Reg No</dt>
                            <dd class="text-sm text-black col-span-2">{{ $company->registration_number ?? '-' }}</dd>
                        </div>
                        <div class="grid grid-cols-3 gap-2 pb-2 border-b border-gray-50 last:border-0 last:pb-0">
                            <dt class="text-sm font-medium text-gray-500 col-span-1">Phone</dt>
                            <dd class="text-sm text-black col-span-2">{{ $company->phone ?? '-' }}</dd>
                        </div>
                    </dl>
                @else
                    <p class="text-sm text-gray-500 italic">No company associated.</p>
                @endif
            </div>

            <!-- Draft Info -->
            <div class="rounded-xl shadow-sm p-6 border border-gray-200 bg-white">
                <h2 class="text-lg font-semibold mb-4 text-black flex items-center gap-2">
                    <flux:icon.document class="w-5 h-5 text-gray-400" />
                    Builder Details
                </h2>
                <dl class="space-y-3">
                    <div class="grid grid-cols-3 gap-2 pb-2 border-b border-gray-50 last:border-0 last:pb-0">
                        <dt class="text-sm font-medium text-gray-500 col-span-1">Draft ID</dt>
                        <dd class="text-sm text-black col-span-2">#{{ $quoteBuilder->id }}</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-2 pb-2 border-b border-gray-50 last:border-0 last:pb-0">
                        <dt class="text-sm font-medium text-gray-500 col-span-1">Created At</dt>
                        <dd class="text-sm text-black col-span-2">{{ $quoteBuilder->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-2 pb-2 border-b border-gray-50 last:border-0 last:pb-0">
                        <dt class="text-sm font-medium text-gray-500 col-span-1">Last Updated</dt>
                        <dd class="text-sm text-black col-span-2">{{ $quoteBuilder->updated_at->format('M d, Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Products List (2/3 width) -->
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-xl shadow-sm border border-gray-200 bg-white overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-black">Configured Products</h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $quoteBuilder->products->count() }} items
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php $grandTotal = 0; @endphp
                            @forelse($quoteBuilder->products as $product)
                                @php
                                    $price = $product->base_price ?: 0;
                                    $qty = $product->pivot->quantity ?: 1;
                                    $subtotal = $price * $qty;
                                    $grandTotal += $subtotal;

                                    // Resolve Cover Image
                                    $coverImage = '/assets/logo.png';
                                    if ($product->images && $product->images->isNotEmpty()) {
                                        $img = $product->images->sortBy('sequence')->first();
                                        $coverImage = $img->image_url ?: ($img->image_path ? (str_starts_with($img->image_path, '/') ? $img->image_path : '/storage/' . $img->image_path) : '/assets/logo.png');
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded border border-gray-100 bg-white flex items-center justify-center shrink-0 overflow-hidden">
                                                <img src="{{ $coverImage }}" alt="{{ $product->title }}" class="max-h-full max-w-full object-contain">
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-black">
                                                    <a href="{{ route('admin.products.show', $product->id) }}" class="hover:underline">
                                                        {{ $product->title }}
                                                    </a>
                                                </div>
                                                <div class="text-xs text-gray-500 font-mono">{{ $product->sku ?: 'No SKU' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-black">
                                        @if($product->is_sign_up_for_pricing)
                                            <span class="text-xs text-blue-600 bg-blue-50 px-2 py-0.5 rounded font-medium">Req Pricing</span>
                                        @else
                                            £{{ number_format($price, 2) }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-black font-semibold">
                                        {{ $qty }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-black">
                                        @if($product->is_sign_up_for_pricing)
                                            -
                                        @else
                                            £{{ number_format($subtotal, 2) }}
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500 italic">
                                        No products in this quote builder draft.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Grand Total Card Footer -->
                @if($quoteBuilder->products->isNotEmpty())
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between items-center">
                        <div class="text-sm font-medium text-gray-500">Estimated Total Value</div>
                        <div class="text-xl font-bold text-black">£{{ number_format($grandTotal, 2) }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Login As User Modal -->
    @if($showLoginModal && $quoteBuilder->user)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-lg mx-4">
                <h3 class="text-lg font-semibold text-black mb-4 flex items-center gap-2">
                    <flux:icon.arrow-right-start-on-rectangle class="w-5 h-5" />
                    Login as {{ $quoteBuilder->user->name }}
                </h3>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start gap-3">
                        <flux:icon.exclamation-triangle class="w-5 h-5 text-yellow-600 mt-0.5" />
                        <div>
                            <p class="text-sm font-medium text-yellow-800">Important: Use Incognito Mode</p>
                            <p class="text-xs text-yellow-700 mt-1">
                                To prevent logging out of your Admin session, please copy the link below and open it in a
                                <strong>New Incognito/Private Window</strong>.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Login URL</label>
                    <div class="flex gap-2">
                        <input type="text" readonly
                            value="{{ URL::signedRoute('admin.users.masquerade', ['userId' => $quoteBuilder->user_id]) }}"
                            class="flex-1 rounded-lg border border-gray-300 bg-gray-50 p-2 text-sm text-gray-600 select-all font-mono">

                        <div x-data="{
                            copy() {
                                navigator.clipboard.writeText('{{ URL::signedRoute('admin.users.masquerade', ['userId' => $quoteBuilder->user_id]) }}');
                                $dispatch('notify', { type: 'success', message: 'Link copied to clipboard!' });
                            }
                        }">
                            <button @click="copy()"
                                class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 text-sm font-medium whitespace-nowrap">
                                Copy Link
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="$set('showLoginModal', false)"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-black hover:bg-gray-50">
                        Close
                    </button>
                    <a href="{{ URL::signedRoute('admin.users.masquerade', ['userId' => $quoteBuilder->user_id]) }}"
                        target="_blank"
                        onclick="return confirm('This will open in a new tab but may log you out of your Admin session. Are you sure?')"
                        class="px-4 py-2 bg-btn-primary text-white rounded-lg hover:bg-btn-primary-hover text-sm font-medium">
                        Open Here Anyway
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
